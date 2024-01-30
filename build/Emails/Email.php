<?php

/**
 * Container for a single email.
 *
 * @since   2.3.0
 * @package Noptin
 */

namespace Hizzle\Noptin\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Container for a single email.
 */
class Email {

	/** @var bool Whether this email is a legacy email. */
	public $is_legacy = false;

	/** @var bool The current admin screen. */
	public $admin_screen = 'email-editor';

	/** @var int Unique identifier for the email. */
	public $id = 0;

	/** @var int Unique identifier for the parent email. */
	public $parent_id = 0;

	/**
	 * @var string|null Schedule date for the email.
	 */
	public $created = null;

	/**
	 * @var string The campaign status.
	 */
	public $status = 'draft';

	/**
	 * @var string The campaign type.
	 */
	public $type = 'newsletter';

	/**
	 * @var string The campaign name.
	 */
	public $name = '';

	/**
	 * @var string The campaign subject.
	 */
	public $subject = '';

	/**
	 * @var string The campaign content.
	 */
	public $content = '';

	/**
	 * @var int The ID for the author of the email.
	 */
	public $author;

	/**
	 * @var array Extra email meta.
	 */
	public $options = array();

	/**
	 * Class constructor.
	 *
	 * @param int|string|array $args
	 */
	public function __construct( $args ) {

		// Creating a new campaign.
		if ( empty( $args ) ) {
			return;
		}

		// Loading a saved campaign.
		if ( is_numeric( $args ) || $args instanceof \WP_Post ) {
			$this->init( $args );
		}

		// Data array.
		if ( is_array( $args ) ) {
			$this->init_args( $args );
		}
	}

	/**
	 * Inits a given email by ID.
	 *
	 * @return bool
	 */
	private function init( $id ) {
		$post = get_post( $id );

		// Abort if the post does not exist.
		if ( empty( $post ) || ! in_array( $post->post_type, array( 'noptin-campaign' ), true ) ) {
			$this->id = 0;
			return false;
		}

		// Fetch campaign data.
		$data        = get_post_meta( $post->ID, 'campaign_data', true );
		$resave      = false;
		$is_revision = wp_is_post_revision( $post->ID );

		// If this is a revision and no data is found, try to fetch the parent data.
		if ( $is_revision && empty( $data ) ) {
			$parent = wp_get_post_parent_id( $post->ID );

			if ( $parent ) {
				$data = get_post_meta( $parent, 'campaign_data', true );
			}
		}

		// If data is stdClass, convert it to an array.
		if ( is_object( $data ) ) {
			$data = (array) $data;
		}

		// Check if we're dealing with a legacy campaign.
		if ( ! is_array( $data ) ) {
			$all_meta = get_post_meta( $post->ID );

			foreach ( $all_meta as $key => $value ) {

				if ( 'noptin_sends_after' === $key ) {
					$key = 'sends_after';
				}

				if ( 'noptin_sends_after_unit' === $key ) {
					$key = 'sends_after_unit';
				}

				if ( in_array( $key, array( 'campaign_type', '_edit_lock', 'automation_type', 'campaign_data' ), true ) ) {
					continue;
				}

				$this->options[ $key ] = $value[0];
			}

			$this->options['email_type']     = 'normal';
			$this->options['content_normal'] = $post->post_content;

			$resave = true;
		} else {
			$this->options = $data;
		}

		$this->id        = $post->ID;
		$this->parent_id = $post->post_parent;
		$this->status    = $post->post_status;
		$this->name      = $post->post_title;
		$this->created   = $post->post_date;
		$this->content   = $post->post_content;
		$this->type      = get_post_meta( $post->ID, 'campaign_type', true );
		$this->author    = $post->post_author;

		// Backwards compatibility.
		// CSS.
		if ( ! isset( $this->options['custom_css'] ) ) {
			if ( 'normal' !== $this->get_email_type() ) {
				$this->options = array_merge(
					get_noptin_email_template_settings( 'noptin-visual', $this ),
					$this->options
				);
			} else {
				$this->options = array_merge(
					get_noptin_email_template_settings( $this->get_template(), $this ),
					$this->options
				);
			}

			$resave = true;
		}

		// Subject.
		$resave_title = false;
		if ( ! isset( $this->options['subject'] ) ) {

			if ( ! empty( $this->options['custom_title'] ) ) {
				$this->name   = $this->options['custom_title'];
				$resave_title = true;

				unset( $this->options['custom_title'] );
			}

			$this->options['subject'] = $post->post_title;
			$resave                   = true;
		}

		if ( $is_revision ) {
			$resave       = false;
			$resave_title = false;
		}

		if ( $resave && 'auto-draft' !== $post->post_status ) {
			// https://core.trac.wordpress.org/ticket/60314.
			update_post_meta( $post->ID, 'campaign_data', (object) $this->options );
		}

		$this->subject = $this->options['subject'];

		// Check if content contains blocks.
		if ( $resave && ! has_blocks( $this->content ) ) {
			$this->content = noptin_email_wrap_blocks(
				empty( $this->content ) ? '' : sprintf(
					'<!-- wp:html -->%s<!-- /wp:html -->',
					wpautop( $this->content )
				),
				$this->get( 'footer_text' ),
				$this->get( 'heading' )
			);

			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_title'   => $this->name,
					'post_content' => $this->content,
				)
			);
		} elseif ( $resave_title ) {
			wp_update_post(
				array(
					'ID'         => $post->ID,
					'post_title' => $this->name,
				)
			);
		}

		// Add sub-type to options array.
		$key                   = $this->type . '_type';
		$this->options[ $key ] = get_post_meta( $post->ID, $key, true );

		// If CRON is not working properly, we need to check if the email is past the scheduled
		// date and update its status.
		if ( 'future' === $this->status && time() > strtotime( $post->post_date_gmt ) ) {
			$this->status = 'publish';
			wp_publish_post( $this->id );
		}

		// Fire action.
		do_action( 'noptin_init_email', $this, $post );

		return true;
	}

	/**
	 * Inits an email with the provided args.
	 *
	 * @return bool
	 */
	private function init_args( $args ) {

		// If we have an id, init the email.
		if ( ! empty( $args['id'] ) ) {
			$this->init( $args['id'] );
			unset( $args['id'] );
		}

		// Reset values.
		foreach ( $args as $key => $value ) {

			if ( property_exists( $this, $key ) ) {
				$this->$key = $value;
				unset( $args[ $key ] );
			}
		}

		// Prepare created date.
		if ( ! empty( $this->created ) ) {
			$this->created = gmdate( 'Y-m-d H:i:s', strtotime( $this->created ) );
		}

		// Merge the remaining args into the options array.
		$this->options = array_merge( $this->options, $args );
	}

	/**
	 * Loads autosaved data for the email.
	 *
	 * @param \WP_Post $post
	 */
	public function load_autosave( $post ) {

		// Fetch campaign data.
		$data = get_post_meta( $post->ID, 'campaign_data', true );

		// If data is stdClass, convert it to an array.
		if ( is_object( $data ) ) {
			$data = (array) $data;
		}

		if ( is_array( $data ) ) {
			$this->options = array_merge( $this->options, $data );
		}

		$this->name    = $post->post_title;
		$this->content = $post->post_content;
	}

	/**
	 * Fetches an emaail.
	 *
	 * @return Email
	 */
	public static function from( $id ) {

		if ( $id instanceof Email ) {
			return $id;
		}

		return new Email( $id );
	}

	/**
	 * Checks if the email exists.
	 *
	 * @return bool
	 */
	public function exists() {
		return ! empty( $this->id );
	}

	/**
	 * Magic getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}

	/**
	 * Retrieves a given setting
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {

		if ( 'id' === strtolower( $key ) ) {
			return $this->id;
		}

		// Fetch value.
		if ( isset( $this->$key ) ) {
			$value = $this->$key;
		} else {
			$value = isset( $this->options[ $key ] ) ? $this->options[ $key ] : '';
		}

		// General filter.
		$value = apply_filters( 'noptin_get_email_prop', $value, $key, $this );

		// Prop specific filtter.
		return apply_filters( "noptin_get_email_prop_$key", $value, $this );
	}

	/**
	 * Returns the sub type for this email.
	 *
	 * @return string
	 */
	public function get_sub_type() {
		return $this->get( $this->type . '_type' );
	}

	/**
	 * Returns the sub types for this email type.
	 *
	 * @return string
	 */
	public function get_sub_types() {
		return get_noptin_campaign_sub_types( $this->type );
	}

	/**
	 * Checks if the email is published.
	 *
	 * @return bool
	 */
	public function is_published() {

		$is_published = 'publish' === $this->status;
		return apply_filters( 'noptin_email_is_published', $is_published, $this->status, $this );
	}

	/**
	 * Checks if the email can send.
	 *
	 * @return bool
	 */
	public function can_send() {

		$can_send = $this->is_published() && $this->exists();

		// Check if the campaign is already sent.
		if ( $can_send && '' !== get_post_meta( $this->id, 'completed', true ) ) {
			$can_send = false;
		}

		// Check if the campaign is paused.
		if ( $can_send && '' !== get_post_meta( $this->id, 'paused', true ) ) {
			$can_send = false;
		}

		return apply_filters( 'noptin_email_can_send', $can_send, $this );
	}

	/**
	 * Checks if this is an automation rule email.
	 *
	 * @return bool
	 */
	public function is_automation_rule() {
		return 0 === strpos( $this->get_sub_type(), 'automation_rule_' );
	}

	/**
	 * Returns the trigger.
	 *
	 * @return bool|string
	 */
	public function get_trigger() {
		return $this->is_automation_rule() ? substr( $this->get_sub_type(), 16 ) : false;
	}

	/**
	 * Returns the contexts for this email.
	 *
	 * @return string[]
	 */
	public function get_contexts() {
		$type = Main::get_email_type( $this->type );

		if ( empty( $type ) ) {
			return array();
		}

		if ( ! $type->supports_sub_types ) {
			return $type->contexts;
		}

		$sub_types = $type->get_sub_types();

		if ( isset( $sub_types[ $this->get_sub_type() ]['contexts'] ) ) {
			return $sub_types[ $this->get_sub_type() ]['contexts'];
		}

		return $type->contexts;
	}

	/**
	 * Checks if this email supports timing.
	 *
	 * @return bool
	 */
	public function supports_timing() {
		return apply_filters( 'noptin_email_supports_timing', $this->supports( 'supports_timing' ), $this );
	}

	/**
	 * Returns the recipient ids for mass mail that are manually sent to selected recipients.
	 *
	 * @return array
	 */
	public function get_manual_recipients_ids() {
		$ids = $this->get( 'manual_recipients_ids' );
		return empty( $ids ) ? array() : array_unique( noptin_parse_int_list( $ids ) );
	}

	/**
	 * Returns the recipients for this email.
	 *
	 * @return string
	 */
	public function get_recipients() {

		// Abort for mass mail.
		if ( $this->is_mass_mail() ) {
			return '';
		}

		// Prepare recipient.
		$recipient = $this->get( 'recipients' );

		// If no recipient, use the default recipient.
		if ( empty( $recipient ) ) {
			$sub_type = $this->get_sub_type();
			return apply_filters( "noptin_default_{$this->type}_email_{$sub_type}_recipient", '', $this );
		}

		return $recipient;
	}

	/**
	 * Returns the placeholder for email recipients.
	 *
	 */
	public function get_placeholder_recipient() {
		$sub_type = $this->get_sub_type();
		$emails   = apply_filters( "noptin_default_{$this->type}_email_{$sub_type}_recipient", '', $this );
		$emails   = trim( $emails . ', ' . get_option( 'admin_email' ) . ' --notracking' );
		$emails   = trim( $emails, ',' );

		if ( empty( $emails ) ) {
			return '';
		}

		// translators: %s: Placeholder for email recipients.
		return sprintf( __( 'For example, %s', 'newsletter-optin-box' ), $emails );
	}

	/**
	 * Returns the sender for this email.
	 *
	 * @return bool
	 */
	public function get_sender() {

		$sender = $this->get( 'email_sender' );
		$sender = ! empty( $sender ) ? $sender : 'noptin';
		return apply_filters( 'noptin_email_sender', $sender, $this );
	}

	/**
	 * Returns the email type for this email.
	 *
	 * @return bool
	 */
	public function get_email_type() {
		$email_type  = $this->get( 'email_type' );
		$email_types = array_keys( get_noptin_email_types() );
		return in_array( $email_type, $email_types, true ) ? $email_type : current( $email_types );
	}

	/**
	 * Returns the email template for this email.
	 *
	 * @return bool
	 */
	public function get_template() {

		$template = $this->get( 'template' );

		// Read from settings.
		if ( empty( $template ) ) {
			$template = get_noptin_option( 'email_template', 'paste' );
		}

		// Default to the paste template.
		if ( empty( $template ) ) {
			$template = 'paste';
		}

		// Filter and return.
		return apply_filters( 'noptin_email_template', $template, $this );
	}

	/**
	 * Returns the subject for this email.
	 *
	 * @return string
	 */
	public function get_subject() {
		return $this->subject;
	}

	/**
	 * Returns the content for this email.
	 *
	 * @return string
	 */
	public function get_content( $email_type = 'normal' ) {

		if ( 'visual' === $email_type ) {
			return $this->content;
		}

		if ( isset( $this->options[ 'content_' . $email_type ] ) ) {
			return $this->options[ 'content_' . $email_type ];
		}

		return '';
	}

	/**
	 * Checks whether the campaign sends immediately.
	 *
	 * @return bool
	 */
	public function sends_immediately() {

		if ( ! $this->supports_timing() || 'immediately' === $this->get( 'when_to_run' ) ) {
			return true;
		}

		return 1 > $this->get_sends_after();
	}

	/**
	 * Returns the delay interval for this automated email.
	 *
	 * @return int
	 */
	public function get_sends_after() {
		return (int) $this->get( 'sends_after' );
	}

	/**
	 * Returns the delay unit for this automated email.
	 *
	 * @param bool $label
	 * @return string
	 */
	public function get_sends_after_unit( $label = false ) {

		$units = get_noptin_email_delay_units( $label && 1 === $this->get_sends_after() );
		$unit  = $this->get( 'sends_after_unit' );

		if ( empty( $unit ) || ! isset( $units[ $unit ] ) ) {
			$unit = 'hours';
		}

		return $label ? $units[ $unit ] : $unit;
	}

	/**
	 * Returns the js data for this email.
	 *
	 * return array
	 */
	public function get_js_data() {
		$manual_recipients = array();
		$email_sender      = $this->get_sender();

		foreach ( $this->get_manual_recipients_ids() as $recipient_id ) {

			$recipient = get_noptin_email_recipient( $recipient_id, $email_sender );

			if ( empty( $recipient ) ) {
				continue;
			}

			$recipient['id']     = $recipient_id;
			$recipient['avatar'] = get_avatar_url( $recipient['email'], array( 'size' => 32 ) );
			$manual_recipients[] = $recipient;
		}

		$data = array(
			'is_automation_rule'    => $this->is_automation_rule(),
			'trigger'               => $this->get_trigger(),
			'supports_timing'       => $this->supports_timing(),
			'placeholder_recipient' => $this->get_placeholder_recipient(),
			'email_type'            => Main::get_email_type( $this->type ),
			'is_mass_mail'          => $this->is_mass_mail(),
			'manual_recipients'     => $manual_recipients,
			'extra_settings'        => (object) apply_filters(
				'noptin_email_extra_settings',
				apply_filters(
					"noptin_{$this->type}_email_extra_settings",
					apply_filters(
						"noptin_{$this->type}_{$this->get_sub_type()}_email_extra_settings",
						array(),
						$this
					),
					$this
				),
				$this
			),
			'merge_tags'            => (object) noptin_prepare_merge_tags_for_js( $this->get_merge_tags() ),
		);

		return apply_filters( 'noptin_email_js_data', $data, $this );
	}

	/**
	 * Returns the email's merge tags.
	 *
	 * @return array
	 */
	public function get_merge_tags() {
		static $tags = array();

		$key = $this->type . '_' . $this->get_sub_type();
		if ( isset( $tags[ $key ] ) ) {
			return $tags[ $key ];
		}

		$tags[ $key ] = apply_filters(
			'noptin_email_merge_tags',
			apply_filters(
				"noptin_{$this->type}_merge_tags",
				apply_filters(
					"noptin_{$this->type}_{$this->get_sub_type()}_merge_tags",
					noptin()->emails->tags->tags,
					$this
				),
				$this
			),
			$this
		);

		return $tags[ $key ];
	}

	/**
	 * Returns the properties as an array.
	 *
	 * @return array
	 */
	public function to_array() {
		$data = get_object_vars( $this );
		return array_merge(
			$data,
			array(
				'options' => array_merge(
					$this->options,
					array(
						'subject' => $this->subject,
					)
				),
			)
		);
	}

	/**
	 * Returns the campaign overview URL.
	 *
	 * @since 2.3.0
	 * @return string.
	 */
	public function get_base_url() {
		$type = Main::get_email_type( $this->type );
		$base = add_query_arg( 'page', 'noptin-email-campaigns', admin_url( '/admin.php' ) );

		if ( empty( $type ) ) {
			return $base;
		}

		// If the type has a parent, add it to the URL.
		if ( ! empty( $type->parent_type ) && ! empty( $this->parent_id ) ) {
			$base = add_query_arg( 'noptin_parent_id', $this->parent_id, $base );
		}

		return add_query_arg( 'noptin_email_type', $type->type, $base );
	}

	/**
	 * Returns a link to edit the campaign.
	 *
	 * @since 1.7.0
	 * @return string.
	 */
	public function get_edit_url() {

		$param = array(
			'noptin_campaign' => $this->id,
		);
		return add_query_arg( $param, $this->get_base_url() );
	}

	/**
	 * Returns a link to preview the campaign.
	 *
	 * @since 1.7.0
	 * @return string.
	 */
	public function get_preview_url( $recipient_email = '' ) {
		return get_noptin_action_url(
			'view_in_browser',
			noptin_encrypt(
				wp_json_encode(
					array(
						'cid'   => $this->id,
						'email' => $recipient_email,
					)
				)
			),
			true
		);
	}

	/**
	 * Prepares the preview content if needed.
	 *
	 * @param string $mode Either 'browser' or 'preview'.
	 * @param array $recipient The recipient meta info.
	 * @return true|\WP_Error
	 */
	public function prepare_preview( $mode, $recipient ) {

		Main::init_current_email_recipient( $recipient );

		try {
			do_action( 'noptin_prepare_email_preview', $mode, $recipient, $this );
			do_action( "noptin_prepare_{$this->type}_email_preview", $mode, $recipient, $this );
			do_action( "noptin_prepare_{$this->type}_{$this->get_sub_type()}_email_preview", $mode, $recipient, $this );
		} catch ( \Exception $e ) {
			return new \WP_Error( 'exception', $e->getMessage() );
		}

		return true;
	}

	/**
	 * Returns a link to duplicate the campaign.
	 *
	 * @since 1.7.0
	 * @return string.
	 */
	public function get_duplication_url() {
		return $this->get_action_url( 'duplicate_campaign' );
	}

	/**
	 * Duplicates the campaign.
	 *
	 * @param array $override An array of properties to override.
	 * @return Email|\WP_Error|false The duplicated campaign or false on failure.
	 */
	public function duplicate( $override = array() ) {
		$args = $this->to_array();

		// Remove id.
		unset( $args['id'] );

		$duplicate = new Email( array_merge( $args, $override ) );
		$result    = $duplicate->save();

		// Check if the duplicate exists.
		if ( ! $duplicate->exists() ) {
			return $result;
		}

		// Duplicate any children.
		foreach ( $this->get_children() as $child ) {
			$child->duplicate( array( 'parent_id' => $duplicate->id ) );
		}

		return $duplicate;
	}

	/**
	 * Fetches the children of this email.
	 *
	 * @return Email[]
	 */
	public function get_children() {

		// Abort if id is not set.
		if ( ! $this->exists() ) {
			return array();
		}

		$posts = get_posts(
			array(
				'post_type'   => 'noptin-campaign',
				'post_parent' => $this->id,
				'numberposts' => -1,
			)
		);

		$emails = array();

		foreach ( $posts as $post ) {
			$emails[] = new Email( $post->ID );
		}

		return $emails;
	}

	/**
	 * Returns an action URL for this email.
	 *
	 * @param string $action The action to perform.
	 * @return string
	 */
	public function get_action_url( $action ) {
		$param = array(
			'noptin_email_action' => $action,
			'noptin_campaign'     => $this->id,
		);
		return wp_nonce_url( add_query_arg( $param, $this->get_base_url() ), 'noptin_email_action', 'noptin_email_action_nonce' );
	}

	/**
	 * Returns a link to delete the campaign.
	 *
	 * @since 1.7.0
	 * @return string.
	 */
	public function get_delete_url() {
		return $this->get_action_url( 'delete_campaign' );
	}

	/**
	 * Deletes the campaign.
	 *
	 * @return Email|\WP_Error|false The deleted campaign or false on failure.
	 */
	public function delete() {

		// Delete any children.
		foreach ( $this->get_children() as $child ) {
			$child->delete();
		}

		do_action( 'noptin_' . $this->type . '_campaign_before_delete', $this );

		// Fire another hook for the sub type.
		do_action( 'noptin_' . $this->get_sub_type() . '_campaign_before_delete', $this->id );

		// Delete the campaign.
		wp_delete_post( $this->id, true );
	}

	/**
	 * Saves the email.
	 */
	public function save() {

		// Prepare post args.
		$args = array(
			'post_type'    => 'noptin-campaign',
			'post_parent'  => $this->parent_id,
			'post_title'   => empty( $this->name ) ? $this->subject : $this->name,
			'post_status'  => $this->status,
			'post_author'  => $this->author,
			'post_content' => $this->content,
			'meta_input'   => array(
				'campaign_type' => $this->type,
				// https://core.trac.wordpress.org/ticket/60314.
				'campaign_data' => (object) array_merge(
					$this->options,
					array(
						'subject' => $this->subject,
					)
				),
			),
		);

		// Store subtype in a separate meta key.
		$args['meta_input'][ $this->type . '_type' ] = $this->get_sub_type();

		// Slash data.
		// WP expects all data to be slashed and will unslash it (fixes '\' character issues).
		$args = wp_slash( $args );

		// Only remove taggeted link rel if it was hooked.
		$has_filter = false !== has_filter( 'content_save_pre', 'wp_targeted_link_rel' );

		if ( $has_filter ) {
			wp_remove_targeted_link_rel_filters();
		}

		// Create or update the email.
		if ( $this->exists() ) {
			$args['ID'] = $this->id;
			$result     = wp_update_post( $args, true );
		} else {
			$result = wp_insert_post( $args, true );
		}

		if ( $has_filter ) {
			wp_init_targeted_link_rel_filters();
		}

		// Schedule the email.
		if ( is_int( $result ) && $result ) {
			$this->init( $result );

			if ( 'future' === $this->status ) {
				wp_clear_scheduled_hook( 'publish_future_post', array( $this->id ) );
				wp_schedule_single_event( strtotime( get_gmt_from_date( $this->created ) . ' GMT' ), 'publish_future_post', array( $this->id ) );
			}
			return true;
		}

		return $result;
	}

	/**
	 * Checks if this is a mass mail.
	 *
	 * @return bool
	 */
	public function is_mass_mail() {
		return apply_filters( "noptin_{$this->type}_is_mass_mail", $this->supports( 'is_mass_mail' ), $this->get_sub_type(), $this );
	}

	/**
	 * Checks if a certain feature is supported for this email.
	 *
	 * @return bool
	 */
	public function supports( $feature ) {

		$type = Main::get_email_type( $this->type );

		if ( ! $type ) {
			return false;
		}

		$sub_type  = $this->get_sub_type();
		$sub_types = $type->get_sub_types();

		if ( ! empty( $sub_type ) && isset( $sub_types[ $sub_type ] ) && isset( $sub_types[ $sub_type ][ $feature ] ) ) {
			return $sub_types[ $sub_type ][ $feature ];
		}

		return isset( $type->{$feature} ) ? $type->{$feature} : false;
	}

	/**
	 * Checks if the current user can edit this email.
	 *
	 * @return bool
	 */
	public function current_user_can_edit() {

		// Return true if not yet saved.
		if ( ! $this->exists() ) {
			return Main::current_user_can_create_new_campaign();
		}

		return current_user_can( 'edit_post', $this->id );
	}

	/**
	 * Checks if the current user can delete this email.
	 *
	 * @return bool
	 */
	public function current_user_can_delete() {

		// Return true if not yet saved.
		if ( ! $this->exists() ) {
			return false;
		}

		return current_user_can( 'delete_post', $this->id );
	}
}
