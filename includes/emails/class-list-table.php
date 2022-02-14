<?php
/**
 * Displays a list of all emails
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Email list table class.
 */
class Noptin_Email_List_Table extends WP_List_Table {

	/**
	 * Current collection.
	 *
	 * @var   string
	 * @since 1.7.0
	 */
	public $collection_type = 'newsletter'; // newsletter, automation

	/**
	 * Query
	 *
	 * @var   WP_Query
	 * @since 1.1.2
	 */
	public $query;

	/**
	 *  Constructor function.
	 */
	public function __construct() {

		if ( isset( $_GET['section'] ) ) {
			$this->collection_type = rtrim( sanitize_key( $_GET['section'] ), 's' );
		}

		$this->prepare_query();

		parent::__construct(
			array(
				'singular' => 'id',
				'plural'   => 'ids',
			)
		);

	}

	/**
	 *  Prepares the display query
	 */
	public function prepare_query() {
		global $noptin_campaigns_query;

		// Campaigns to display on every page.
		$per_page = 10;

		// Prepare query params.
		$paged   = empty( $_GET['paged'] ) ? 1 : (int) $_GET['paged'];
		$orderby = empty( $_GET['orderby'] ) ? 'id' : sanitize_text_field( $_GET['orderby'] );
		$order   = empty( $_GET['order'] ) ? 'desc' : sanitize_text_field( $_GET['order'] );

		$query_args = array(
			'post_type'      => 'noptin-campaign',
			'post_status'    => array( 'pending', 'draft', 'future', 'publish' ),
			'meta_key'       => 'campaign_type',
			'meta_value'     => $this->collection_type,
			'orderby'        => $orderby,
			'order'          => $order,
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);
		$query_args = apply_filters( 'manage_noptin_emails_wp_query_args', $query_args, $this );

		$noptin_campaigns_query = new WP_Query( $query_args );
		$this->query            = $noptin_campaigns_query;

	}

	/**
	 * Default columns.
	 *
	 * @param object $item        item.
	 * @param string $column_name column name.
	 */
	public function column_default( $item, $column_name ) {

		/**
		 * Displays a given column
		 *
		 * @param array $this The admin instance
		 */
		do_action( "noptin_display_emails_table_$column_name", $item, $this );

	}

	/**
	 * Displays the newsletter name
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_title( $item ) {

		// Prepare row actions.
		$row_actions = array(

			'edit'   => sprintf(
				'<a href="%s">%s</a>',
				esc_url( $item->get_edit_url() ),
				esc_html__( 'Edit', 'newsletter-optin-box' )
			),

			'duplicate' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\');">%s</a>',
				$item->get_duplication_url(), // This is alread escaped via wp_nonce_url.
				esc_attr__( 'Are you sure you want to duplicate this campaign?', 'newsletter-optin-box' ),
				esc_html__( 'Duplicate', 'newsletter-optin-box' )
			),

			'_preview' => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( $item->get_preview_url() ),
				esc_html__( 'Preview', 'newsletter-optin-box' )
			),

			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\');">%s</a>',
				$item->get_duplication_url(), // This is alread escaped via wp_nonce_url.
				esc_attr__( 'Are you sure you want to delete this campaign?', 'newsletter-optin-box' ),
				esc_html__( 'Delete', 'newsletter-optin-box' )
			),

		);

		// Sent newsletters are not editable.
		if ( 'newsletter' === $this->collection_type && $item->is_published() ) {
			unset( $row_actions['edit'] );
			$edit_url = $item->get_preview_url();
		} else {
			$edit_url = $item->get_edit_url();
		}

		$title = $item->get( 'custom_title' );
		$title = empty( $title ) ? esc_html( $item->name ) : $title;

		$title = "<div><strong><a href='$edit_url'>$title</a></strong></div>";

		// About automation.
		if ( 'automation' === $this->collection_type ) {
			$description = wp_kses_post( apply_filters( 'noptin_automation_table_about_' . $item->type, '', $item, $this ) );

			if ( ! empty( $description ) ) {
				$title .= "<p class='description'>$description</div>";
			}

		}

		// Row actions.
		$row_actions = apply_filters( 'noptin_email_row_actions', $row_actions, $item, $this );
		if ( ! empty( $row_actions ) ) {
			$title .= '<div class="row-actions">' . $this->row_actions( $row_actions ) . '</div>';
		}

		return $title;
	}

	/**
	 * This is how checkbox column renders.
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="id[]" value="%s" />', esc_html( $item->id ) );
	}

	/**
	 * Displays the campaign status
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return void
	 */
	public function column_status( $item ) {
		$status = __( 'Draft', 'newsletter-optin-box' );

		if ( 'future' === $item->status ) {
			$status = __( 'Scheduled', 'newsletter-optin-box' );
		}

		if ( 'publish' === $item->status ) {

			if ( get_post_meta( $item->id, 'completed', true ) ) {
				$status = __( 'Sent', 'newsletter-optin-box' );
			} else {
				$status = '<strong style="color: #00796b;">' . __( 'Sending', 'newsletter-optin-box' ) . '</strong>';

				if ( 'newsletter' === $this->collection_type ) {
					$status .= '&mdash;<a class="noptin-stop-campaign" href="#" data-id="' . $item->id . '">' . __( 'stop', 'newsletter-optin-box' ) . '</a>';
				}

			}

		}

		$status = apply_filters( 'noptin_admin_table_email_status', $status, $item );
		echo "<span>$status</span>";
	}

	/**
	 * Displays the newsletter's date sent day
	 *
	 * @param  Noptin_Newsletter_Email $item item.
	 * @return void
	 */
	public function column_date_sent( $item ) {
		$date = '&mdash;';

		if ( 'future' === $item->post_status ) {

			$date = sprintf(
				__( 'Scheduled to send in %s', 'newsletter-optin-box' ),
				'<br /><strong>' . human_time_diff( strtotime( $item->created ), current_time( 'timestamp' ) ) . '</strong>'
			);

			// In case CRON is not working.
			if ( strtotime( $item->created ) < current_time( 'timestamp' ) ) {
				wp_publish_post( $item );
			}

		}

		if ( 'publish' === $item->post_status ) {
			$date = date_i18n( get_option( 'date_format' ), strtotime( $item->created ) );
		}

		$title = esc_attr( $item->created );
		echo "<abbr title='$title'>$date</abbr>";
	}

	/**
	 * Displays the campaign recipients
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_recipients( $item ) {

		$sent = (int) get_post_meta( $item->id, '_noptin_sends', true );
		$sent = $this->maybe_link( $sent, "_campaign_{$item->id}", '1' );

		$failed = (int) get_post_meta( $item->id, '_noptin_fails', true );
		$failed = $this->maybe_link( $failed, "_campaign_{$item->id}", '0' );

		$sent   = empty( $failed ) ? $sent : "$sent ($failed failed)";

		return apply_filters( 'noptin_email_recipients', $sent, $item );

	}

	/**
	 * Displays the campaign type
	 *
	 * @param  Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_type( $item ) {

		if ( isset( noptin()->emails->automated_email_types->types[ $item->type ] ) ) {
			return noptin()->emails->automated_email_types->types[ $item->type ]->get_name();
		} else {
			return __( 'Unknown', 'newsletter-optin-box' );
		}

	}

	/**
	 * Displays the campaign opens
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_opens( $item ) {

		$opens = (int) get_post_meta( $item->id, '_noptin_opens', true );
		$opens = $this->maybe_link( $opens, "_campaign_{$item->id}_opened", '1' );
		return apply_filters( 'noptin_email_opens', $opens, $item );

	}

	/**
	 * Displays the campaign clicks
	 *
	 * @param  Noptin_Newsletter_Email|Noptin_Automated_Email $item item.
	 * @return HTML
	 */
	public function column_clicks( $item ) {

		$clicks = (int) get_post_meta( $item->id, '_noptin_clicks', true );
		$clicks = $this->maybe_link( $clicks, "_campaign_{$item->id}_clicked", '1' );
		return apply_filters( 'noptin_email_clicks', $clicks, $item );

	}

	/**
	 * Links to the subscribers overview page.
	 *
	 * @param  int    $count The number to link.
	 * @param  string $meta The subscriber meta key to filter by.
	 * @param  string $value The subscriber meta value to filter by.
	 * @return HTML
	 */
	public function maybe_link( $count, $meta, $value ) {

		if ( empty( $count ) ) {
			return 0;
		}

		$url = esc_url(
			add_query_arg(
				array(
					'meta_key'   => $meta,
					'meta_value' => $value,
				),
				get_noptin_subscribers_overview_url()
			)
		);

		return "<a href='$url' title='View Subscribers'>$count</a>";

	}

	/**
	 * [OPTIONAL] Return array of bult actions if has any
	 *
	 * @return array
	 */
	public function get_bulk_actions() {

		$actions = array(
			'delete' => __( 'Delete', 'newsletter-optin-box' ),
		);
		return apply_filters( 'manage_noptin_emails_table_bulk_actions', $actions );

	}

	/**
	 * Whether the table has items to display or not
	 *
	 * @return bool
	 */
	public function has_items() {
		return $this->query->have_posts();
	}

	/**
	 * Generate the table rows
	 *
	 * @since 1.1.2
	 */
	public function display_rows() {
		foreach ( $this->query->get_posts() as $post ) {

			if ( 'newsletter' === $this->collection_type ) {
				$post = new Noptin_Newsletter_Email( $post->ID );
			} else {
				$post = new Noptin_Automated_Email( $post->ID );
			}

			$this->single_row( $post );
		}
	}

	/**
	 * Fetch data from the database to render on view.
	 */
	function prepare_items() {

		$per_page = 10;

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$this->set_pagination_args(
			array(
				'total_items' => $this->query->found_posts,
				'per_page'    => $per_page,
				'total_pages' => $this->query->max_num_pages,
			)
		);

	}

	/**
	 * Table columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		// Prepare columns.
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'title'      => 'newsletter' == $this->collection_type ? __( 'Email Subject', 'newsletter-optin-box' ) : __( 'Name', 'newsletter-optin-box' ),
			'type'       => __( 'Type', 'newsletter-optin-box' ),
			'status'     => __( 'Status', 'newsletter-optin-box' ),
			'recipients' => 'newsletter' == $this->collection_type ? __( 'Recipients', 'newsletter-optin-box' ) : __( 'Sent', 'newsletter-optin-box' ),
			'opens'      => __( 'Opens', 'newsletter-optin-box' ),
			'clicks'     => __( 'Clicks', 'newsletter-optin-box' ),
			'date_sent'  => __( 'Sent on', 'newsletter-optin-box' ),
		);

		// Remove tracking stats.
		$track_campaign_stats = get_noptin_option( 'track_campaign_stats', true );

		if ( empty( $track_campaign_stats ) ) {
			unset( $columns['opens'] );
			unset( $columns['clicks'] );
		}

		// Remove automation details for newsletters.
		if ( 'automation' != $this->collection_type ) {
			unset( $columns['type'] );
		} else {
			unset( $columns['date_sent'] );
		}

		return apply_filters( 'manage_noptin_emails_table_columns', $columns, $this );
	}

	/**
	 * Table sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable = array(
			'id'        => array( 'id', true ),
			'title'     => array( 'post_title', true ),
			'date_sent' => array( 'post_date', true ),
		);
		return apply_filters( 'manage_noptin_emails_sortable_table_columns', $sortable );
	}

	/**
	 * Message to be displayed when there are no items
	 */
	public function no_items() {
		$add_new_campaign_url = get_noptin_new_newsletter_campaign_url();

		echo "<div style='min-height: 320px; display: flex; align-items: center; justify-content: center; flex-flow: column;'>";
		echo "<span class='dashicons dashicons-email' style='font-size: 100px; height: 100px; width: 100px; color: #00acc1; line-height: 100px;'></span>";
		
		printf(
			/* Translators: %1$s Opening link tag, %2$s Closing link tag. */
			__( '%1$sSend your subscribers a new email%2$s', 'newsletter-optin-box' ),
			"<a class='no-campaign-create-new-campaign' href='$add_new_campaign_url'>",
			'</a>'
		);

		echo "<p class='description'>Or <a style='color: #616161; text-decoration: underline;' href='https://noptin.com/guide/sending-emails' target='_blank'>" . __( 'Learn more', 'newsletter-optin-box' ) . "</a></p>";
		echo '</div>';

	}

	/**
	 * Message to be displayed when there are no items (newsletters)
	 */
	public function no_items_newsletter() {

		$params               = array(
			'page'        => 'noptin-email-campaigns',
			'section'     => 'newsletters',
			'sub_section' => 'new_campaign',
		);
		$add_new_campaign_url = add_query_arg( $params, admin_url( '/admin.php' ) );;

		echo "<div style='min-height: 320px; display: flex; align-items: center; justify-content: center; flex-flow: column;'>";
		echo "<span class='dashicons dashicons-email' style='font-size: 100px; height: 100px; width: 100px; color: #00acc1; line-height: 100px;'></span>";
		
		printf(
			/* Translators: %1$s Opening link tag, %2$s Closing link tag. */
			__( '%1$sSend your subscribers a new email%2$s', 'newsletter-optin-box' ),
			"<a class='no-campaign-create-new-campaign' href='$add_new_campaign_url'>",
			'</a>'
		);

		echo "<p class='description'>Or <a style='color: #616161; text-decoration: underline;' href='https://noptin.com/guide/sending-emails' target='_blank'>" . __( 'Learn more', 'newsletter-optin-box' ) . "</a></p>";
		echo '</div>';

	}

}
