<?php
/**
 * Noptin.com Connection Providers Class.
 *
 * @package Noptin\noptin.com
 * @since   1.5.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Noptin_Connection_Provider Class
 *
 * @since 1.5.1
 * @ignore
 */
abstract class Noptin_Connection_Provider extends Noptin_Abstract_Integration {

	/**
	 * @var int The priority for hooks.
	 * @since 1.5.1
	 */
	public $priority = 100;

	/**
	 * @var string type of integration.
	 * @since 1.5.1
	 */
	public $integration_type = 'esp';

	/**
	 * @var string last error message.
	 * @since 1.5.1
	 */
	protected $last_error = '';

	/**
	 * @var Noptin_List_Providers Main list providers factory.
	 * @since 1.5.1
	 */
	public $list_providers;

	/**
	 * @var array
	 * @since 1.5.1
	 */
	public $supports = array();

	/**
	 * Checks if we're connected to the provider.
	 *
	 * @since 1.5.1
	 */
	abstract public function is_connected();

	/**
	 * Checks if a given feature is supported.
	 *
	 * @since 1.5.1
	 */
	public function supports( $feature ) {
		return in_array( $feature, $this->supports );
	}

	/**
	 * This method is called after an integration is initialized.
	 *
	 * @since 1.5.1
	 */
	public function initialize() {

		// Register this provider.
		add_filter( 'noptin_connection_providers', array( $this, 'register_provider' ), $this->priority );

		if ( ! $this->is_connected() || empty( $this->list_providers ) ) {
			return;
		}

		// Automation rules.
		add_action( 'noptin_automation_rules_load', array( $this, 'load_automation_rules' ), $this->priority );

		// New subscribers.
		add_action( 'noptin_insert_subscriber', array( $this, 'add_subscriber'), $this->priority, 2 );

		add_filter( 'noptin_single_integration_settings', array( $this, 'add_list_options' ), $this->priority, 3 );

		// Send campaigns.
		if ( $this->supports( 'campaigns' ) ) {
			add_filter( 'add_meta_boxes_noptin_newsletters', array( $this, 'register_newsletter_recipients_filter_meta_box' ), $this->priority );
			add_filter( 'add_meta_boxes_noptin_automations_post_notifications', array( $this, 'register_automation_recipients_filter_meta_box' ), $this->priority );
			add_filter( 'noptin_save_newsletter_campaign_details', array( $this, 'save_newsletter_recipients' ), $this->priority );
			add_filter( 'noptin_mailer_new_post_automation_campaign_details', array( $this, 'filter_new_post_data' ), $this->priority );
			add_filter( 'noptin_should_send_campaign', array( $this, 'send_via_provider' ), $this->priority, 2 );
		}

	}

	/**
	 * Loads automation rules.
	 *
	 * @param Noptin_Automation_Rules $automation_rules
	 * @since 1.5.1
	 */
	public function load_automation_rules( $automation_rules ) {

		$automation_rules->add_action( new Noptin_Connection_Provider_Add_List_Action( $this ) );
		$automation_rules->add_action( new Noptin_Connection_Provider_Remove_List_Action( $this ) );

		if ( $this->supports( 'tags' ) ) {
			$automation_rules->add_action( new Noptin_Connection_Provider_Add_Tags_Action( $this ) );
			$automation_rules->add_action( new Noptin_Connection_Provider_Remove_Tags_Action( $this ) );
		}

		foreach ( $this->list_providers->get_secondary() as $secondary => $is_universal ) {
			$automation_rules->add_action( new Noptin_Connection_Provider_Add_Secondary_List_Action( $this, $secondary, $is_universal ) );
		}

	}

	/**
	 * Adds a new Noptin subscriber to the provider.
	 *
	 * @since 1.5.1
	 */
	public function add_subscriber( $subscriber_id, $data = array() ) {

		// Retrieve the Noptin subscriber.
		$noptin_subscriber = new Noptin_Subscriber( $subscriber_id );
		if ( ! $noptin_subscriber->exists() ) {
			return;
		}

		// Fetch appropriate list.
		$data             = $this->prepare_new_subscriber_data( $noptin_subscriber, $data );
		$integration_data = empty( $data[ $this->slug ] ) ? array() : $data[ $this->slug ];
		$lists            = empty( $integration_data['lists'] ) ? $this->get_default_list_id() : $integration_data['lists'];

		if ( ! is_array( $lists ) ) {
			$lists = array( $lists );
		}

		if ( empty( $lists ) || in_array( '-1', $lists ) ) {
			return;
		}

		$integration_data['double_optin'] = $this->enabled_double_optin();

		foreach ( array_keys( $this->list_providers->get_secondary() ) as $secondary ) {

			if ( empty( $integration_data[ $secondary ] ) ) {
				$integration_data[ $secondary ] = noptin_parse_list( get_noptin_option( "noptin_{$this->slug}_default_{$secondary}", '' ), true );
			}

		}

		if ( empty( $integration_data['tags'] ) ) {
			$integration_data['tags'] = noptin_parse_list( get_noptin_option( "noptin_{$this->slug}_default_tags", '' ), true );
		}

		$integration_data['tags'] = is_array( $integration_data['tags'] ) ? array_filter( $integration_data['tags'] ) : array();

		// Add the subscriber to the list(s).
		try {

			foreach ( $lists as $list_id ) {
				$list = $this->list_providers->get_list( trim( $list_id ) );

				if ( ! empty( $list ) ) {
					$list->add_subscriber( $noptin_subscriber, $integration_data );
				}

			}

			if ( $this->supports( 'universal_tags' ) && ! empty( $integration_data['tags'] ) ) {
				$this->list_providers->tag_subscriber( $noptin_subscriber, $integration_data['tags'] );
			}

		} catch ( Exception $ex ) {
			$this->last_error = $ex->getMessage();
			log_noptin_message( $ex->getMessage() );
		}

	}

	/**
	 * Returns an array of subscriber fields.
	 *
	 * @param Noptin_Subscriber $subscriber
	 * @param array $data
	 * @since 1.5.1
	 * @return array
	 */
	public function prepare_new_subscriber_data( $subscriber, $data ) {

		if ( ! is_array( $data ) ) {
			$data = array();
		}

		if ( empty( $data[ $this->slug ] ) ) {
			$data[ $this->slug ] = array();
		}

		$form = $subscriber->get( '_subscriber_via' );

		if ( empty( $form ) ) {
			return $data;
		}

		if ( ! is_numeric( $form ) ) {
			$list = get_noptin_option( sanitize_text_field( "noptin_{$this->slug}_{$form}_default_list" ) );
			$tags = get_noptin_option( sanitize_text_field( "noptin_{$this->slug}_{$form}_default_tags" ) );

			// Suscriber tags.
			if ( $this->supports( 'tags' ) && ! isset( $data[ $this->slug ]['tags'] ) && ! empty( $tags ) ) {
				$data[ $this->slug ]['tags'] = array_map( 'trim', explode( ',', $tags ) );
			}

			// Suscriber list.
			if ( ! isset( $data[ $this->slug ]['lists'] ) && ! empty( $list ) ) {
				$data[ $this->slug ]['lists'] = array_map( 'trim', explode( ',', $list ) );
			}

			foreach ( array_keys( $this->list_providers->get_secondary() ) as $secondary ) {
				$defaults = noptin_parse_list( get_noptin_option( "noptin_{$this->slug}_{$form}_default_{$secondary}", '' ), true );
				$data[ $this->slug ][ $secondary ] = noptin_clean( $defaults );
			}

			return $data;
		}

		$form = absint( $form );
		$form = noptin_get_optin_form( $form );

		// Ensure the form exists.
		if ( ! $form->is_published() ) {
			return $data;
		}

		// Merge fields.
		$fields = array();
		foreach ( $form->fields  as $field ) {

			if ( empty( $field[ $this->slug ] ) ) {
				continue;
			}

			$remote = trim( $field[ $this->slug ] );
			$name   = trim( $field['type']['name'] );

			if ( ! empty( $remote ) && ! empty( $subscriber->$name ) ) {
				$fields[ $remote ] = $subscriber->$name;
			}

		}

		$data[ $this->slug ]['fields'] = $fields;

		// Suscriber tags.
		$tags = "{$this->slug}_tags";
		if ( $this->supports( 'tags' ) && ! empty( $form->$tags ) ) {
			$data[ $this->slug ]['tags'] = array_map( 'trim', explode( ',', $form->$tags ) );
		}

		// Suscriber list.
		$list = "{$this->slug}_list";
		if ( ! empty( $form->$list ) ) {
			$data[ $this->slug ]['lists'] = array_map( 'trim', explode( ',', $form->$list ) );
		}

		foreach ( array_keys( $this->list_providers->get_secondary() ) as $secondary ) {
			$key = sanitize_key( "{$this->slug}_{$secondary}" );

			if ( ! empty( $form->$key ) ) {
				$data[ $this->slug ][ $secondary ] = noptin_parse_list ( $form->$key, true );
			}

		}

		return $data;
	}

	/**
	 * Returns the default list id.
	 *
	 * @since 1.5.1
	 * @return string
	 */
	public function get_default_list_id() {
		return get_noptin_option( 'noptin_' . $this->slug .'_enable_default_list', '-1' );
	}

	/**
	 * Registers the provider.
	 *
	 * @since 1.5.1
	 * @param Noptin_Connection_Provider[] $providers
	 * @return Noptin_Connection_Provider[]
	 */
	public function register_provider( $providers ) {
		$providers[ $this->slug ] = $this;
		return $providers;
	}

	/**
	 * Registers integration options.
	 *
	 * @since 1.5.1
	 * @param array $_options Current Noptin settings.
	 * @return array
	 */
	public function add_options( $_options ) {

		$slug    = $this->slug;
		$options = $this->add_enable_integration_option( array() );

		if ( ! $this->supports( 'oauth' ) ) {
			$options = $this->add_connection_options( $options );
		}

		if ( $this->is_connected() ) {

			// Double optin.
			if ( $this->supports( 'double_optin' ) ) {

				$options["noptin_{$slug}_enable_double_optin"] = array(
					'type'                  => 'checkbox_alt',
					'el'                    => 'input',
					'section'		        => 'integrations',
					'label'                 => __( 'Enable double opt-in', 'newsletter-optin-box' ),
					'description'           => __( 'Send contacts an opt-in confirmation email when they sign up', 'newsletter-optin-box' ),
					'restrict'              => $this->get_enable_integration_option_name(),
				);

			}

			// Default list.
			if ( ! empty( $this->list_providers ) ) {

				$options["noptin_{$slug}_enable_default_list"] = array(
					'section'	  => 'integrations',
					'el'          => 'select',
					'options'     => $this->list_providers->get_dropdown_lists(),
					'placeholder' => sprintf( __( 'Select a default %s', 'newsletter-optin-box' ), $this->list_providers->get_name() ),
					'label'       => sprintf( __( 'Default %s', 'newsletter-optin-box' ), $this->list_providers->get_name() ),
					'restrict'    => $this->get_enable_integration_option_name(),
				);

			}

			// Tags.
			if ( $this->supports( 'tags' ) ) {

				$options["noptin_{$slug}_default_tags"] = array(
					'el'                    => 'input',
					'section'		        => 'integrations',
					'label'                 => __( 'Default tags', 'newsletter-optin-box' ),
					'description'           => __( 'Enter a comma separated list of default tags to assign new suscribers.', 'newsletter-optin-box' ),
					'restrict'              => $this->get_enable_integration_option_name(),
					'placeholder' => 'tag 1, tag 2',
				);

			}

			foreach ( $this->list_providers->get_secondary() as $secondary => $is_universal ) {

				if ( $is_universal ) {

					$options["noptin_{$slug}_default_{$secondary}"] = array(
						'el'          => 'multiselect',
						'label'       => sprintf( __( 'Default %s', 'newsletter-optin-box' ), $secondary ),
						'options'     => $this->list_providers->get_dropdown( $secondary ),
					);

				} else {

					$options["noptin_{$slug}_default_{$secondary}"] = array(
						'el'          => 'input',
						'label'       => sprintf( __( 'Default %s (ids)', 'newsletter-optin-box' ), $secondary ),
					);

				}

			}

			// Extra integration options.
			$options = $this->get_options( $options );

		}

		$options = apply_filters( "noptin_single_integration_settings", $options, $slug, $this );

		if ( $this->supports( 'oauth' ) ) {
			$options = $this->add_connection_options( $options );
		}

		$_options["settings_section_$slug"] = array(
			'id'          => "settings_section_$slug",
			'el'          => 'settings_section',
			'children'    => $options,
			'section'     => 'integrations',
			'heading'     => sanitize_text_field( $this->name ),
			'description' => sanitize_text_field( $this->description ),
			'badge'       => $this->get_hero_extra(),
		);

		return apply_filters( "noptin_{$slug}_integration_settings", $_options, $this );

	}

	/**
	 * Adds connection options to settings fields.
	 *
	 * @since 1.5.1
	 * @return false
	 */
	public function add_connection_options( $options ) {
		return $options;
	}

	/**
	 * Extra setting fields.
	 *
	 * @since 1.5.1
	 * @return false
	 */
	public function get_options( $options ) {
		return $options;
	}

	/**
	 * Returns extra texts to append to the hero
	 *
	 * @return string
	 * @since 1.5.1
	 */
	public function get_hero_extra() {

		$option   = $this->get_enable_integration_option_name();
		$disabled = __( 'Disabled', 'newsletter-optin-box' );

		if ( $this->is_connected() ) {
			$enabled = __( 'Connected', 'newsletter-optin-box' );
		} else {

			if ( ! empty( $this->last_error ) ) {
				$enabled = __( 'Not Connected', 'newsletter-optin-box' );
				$enabled = "$enabled <em>( {$this->last_error} )</em>";
			} else {
				$enabled = __( 'Enabled', 'newsletter-optin-box' );
			}

		}

		return "
			<span style='color: #43a047;' v-if='$option'>$enabled</span>
			<span style='color: #616161;' v-else>$disabled</span>
		";

	}

	/**
	 * Checks if double opt-in is enabled.
	 *
	 * @since 1.5.1
	 * @return bool
	 */
	public function enabled_double_optin() {
		return (bool) get_noptin_option( "noptin_{$this->slug}_enable_double_optin", false );
	}

	/**
	 * Returns connection specific form settings.
	 *
	 * @param array $options Current Noptin settings.
	 * @return array
	 */
	public function add_custom_options( $options ) {
		return $options;
	}

	/**
	 * Returns connection specific form state.
	 *
	 * @param array $options Current Noptin settings.
	 * @return array
	 */
	public function add_custom_default_form_state( $options ) {
		return $options;
	}

	/**
	 * Returns connection specific default field props.
	 *
	 * @param array $default Default field props.
	 * @return array
	 */
	public function add_custom_field_props( $default ) {
		return $default;
	}

	/**
	 * Registers the newsletter recipients list filter box.
	 *
	 */
	public function register_newsletter_recipients_filter_meta_box() {

		add_meta_box (
			'noptin_newsletter_' . $this->slug,
			$this->name,
			array( $this, 'render_newsletter_metabox' ),
			'noptin_page_noptin-newsletter',
			'side'
		);

	}

	/**
	 * Registers the automation recipients list filter box.
	 *
	 */
	public function register_automation_recipients_filter_meta_box() {

		add_meta_box (
			'noptin_automation_' . $this->slug,
			$this->name,
			array( $this, 'render_newsletter_metabox' ),
			'noptin_page_noptin-automation',
			'side'
		);

	}

	/**
	 * Retrieves extra campaign data.
	 *
	 * @param null|WP_Post $campaign
	 */
	public function get_extra_campaign_data( $campaign ) {

		if ( empty( $campaign ) ) {
			return array();
		}

		$extra_key = sanitize_key( 'noptin_' . $this->slug . '_extra' );
		$extra     = is_object( $campaign ) ? get_post_meta( $campaign->ID, $extra_key, true ) : array();

		if ( ! is_array( $extra ) ) {
			return array();
		}

		return $extra;
	}

	/**
	 * Displays a newsletter recipients meta box.
	 *
	 * @param null|WP_Post $campaign
	 */
	public function render_newsletter_metabox( $campaign ) {
		$key       = sanitize_key( 'noptin_' . $this->slug . '_list' );
		$list      = is_object( $campaign ) ? get_post_meta( $campaign->ID, $key, true ) : '';
		$tags_key  = sanitize_key( 'noptin_' . $this->slug . '_tags' );
		$tags      = is_object( $campaign ) ? get_post_meta( $campaign->ID, $tags_key, true ) : '';
		$extra     = $this->get_extra_campaign_data( $campaign );
		?>

			<div style="margin: 16px 0;" class="noptin-<?php echo esc_attr( $this->slug );?>-list">

				<label style="display: block;">
					<strong><?php echo esc_html( ucwords( $this->list_providers->get_name() ) ); ?></strong>
					<select name="<?php echo $key; ?>" style="width: 100%;" class="list-select">
						<option value="0" <?php selected( empty( $list ) ) ?>><?php _e( 'Do not send', 'newsletter-optin-box' );?></option>
						<?php foreach ( $this->list_providers->get_dropdown_lists() as $id => $name ) :?>
							<option value="<?php echo esc_attr( $id ) ?>" <?php selected( $id, $list ) ?>><?php echo esc_html( $name );?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php printf( __( 'Select a %s if you want to send this campaign via %s.', 'newsletter-optin-box' ), $this->list_providers->get_name(), $this->name );?></p>
				</label>

				<?php if ( $this->supports( 'tags' ) ) : ?>
					<label style="display: block;">
						<strong><?php _e( 'Tags', 'newsletter-optin-box' ); ?></strong>
						<input style="width: 100%;" type="text" value="<?php echo esc_attr( $tags ) ?>" name="<?php echo $tags_key; ?>" />
					</label>
				<?php endif; ?>

				<?php foreach ( $this->list_providers->get_lists() as $_list ) : ?>
					<?php foreach ( $_list->get_children() as $child_id => $child ) : ?>
						<?php $value = isset( $extra[ $_list->get_id() ][ $child_id ] ) ? $extra[ $_list->get_id() ][ $child_id ] : ''?>
						<div class="noptin-filter-list noptin-<?php echo esc_attr( $this->slug );?>-filter-list noptin-list-<?php echo esc_attr( $_list->get_id() ) ?>">
							<label><strong><?php echo esc_html( $child['label'] ); ?></strong>
								<select name="noptin_<?php echo esc_attr( $this->slug );?>_extra[<?php echo esc_attr( $_list->get_id() );?>][<?php echo esc_attr( $child_id );?>]" style="width: 100%;">
									<option <?php selected( empty( $value ) ) ?> value='0'>
										<?php _e( 'All', 'newsletter-optin-box' ); ?>
									</option>
									<?php foreach ( $child['options'] as $id => $name ) { ?>
										<option <?php selected( $value, $id ) ?> value='<?php echo esc_attr( $id );?>'>
											<?php echo sanitize_text_field( $name ); ?>
										</option>
									<?php } ?>
								</select>
							</label>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>

			<script>
				var sync = function() {

					var parent   = jQuery('.noptin-<?php echo esc_js( $this->slug );?>-list')
					var selected = parent.find( '.list-select' ).val()

					// Hide list filter options.
					parent.find('.noptin-<?php echo esc_attr( $this->slug );?>-filter-list').hide()

					// Show available filters.
					parent.find( '.noptin-list-' + selected ).show()

				}
				sync()
				jQuery('.noptin-<?php echo esc_js( $this->slug );?>-list .list-select').on( 'change', sync )
			</script>
		<?php
	}

	/**
	 * Saves newsletter recipients.
	 *
	 * @param array $campaign
	 */
	public function save_newsletter_recipients( $campaign ) {

		// Lists.
		$key  = sanitize_key( 'noptin_' . $this->slug . '_list' );
		$list = empty( $_POST[ $key ] ) ? '' : sanitize_text_field( $_POST[ $key ] );

		$campaign['meta_input'][ $key ]  = $list;

		// Tags.
		if ( $this->supports( 'tags' ) ) {
			$tags_key = sanitize_key( 'noptin_' . $this->slug . '_tags' );
			$tags     = empty( $_POST[ $tags_key ] ) ? '' : sanitize_text_field( $_POST[ $tags_key ] );

			$campaign['meta_input'][ $tags_key ]  = $tags;
		}

		$key   = sanitize_key( 'noptin_' . $this->slug . '_extra' );
		$extra = empty( $_POST[ $key ] ) ? array() : sanitize_text_field( $_POST[ $key ] );

		$campaign['meta_input'][ $key ]  = $extra;

		return $campaign;
	}

	/**
	 * Filters new post notification data.
	 *
	 * @param array $campaign_data
	 */
	public function filter_new_post_data( $campaign_data ) {
		$campaign_id = $campaign_data['meta_input']['campaign_id'];
		$key         = sanitize_key( 'noptin_' . $this->slug . '_list' );
		$list        = get_post_meta( $campaign_id, $key, true );

		if ( empty( $list ) ) {
			return $campaign_data;
		}

		$campaign_data['meta_input'][ $key ] = $list;

		$tags_key = sanitize_key( 'noptin_' . $this->slug . '_tags' );
		$tags     = get_post_meta( $campaign_id, $tags_key, true );

		if ( ! empty( $tags ) ) {
			$campaign_data['meta_input'][ $tags_key ]  = $tags;
		}

		$key   = sanitize_key( 'noptin_' . $this->slug . '_extra' );
		$extra = get_post_meta( $campaign_id, $key, true );

		if ( ! empty( $extra ) ) {
			$campaign_data['meta_input'][ $key ]  = $extra;
		}

		return $campaign_data;
	}

	/**
	 * Sends the newsletter via this connection.
	 *
	 * @param bool $should_send
	 * @param array $campaign
	 */
	public function send_via_provider( $should_send, $campaign ) {

		// Do we have a campaign id?
		if ( empty( $campaign['campaign_id'] ) ) {
			return $should_send;
		}

		$key  = sanitize_key( 'noptin_' . $this->slug . '_list' );
		$list = get_post_meta( $campaign['campaign_id'], $key, true );

		if ( empty( $list ) ) {
			return $should_send;
		}

		update_post_meta( $campaign['campaign_id'], 'completed', 1 );

		$list = $this->list_providers->get_list( $list );

		if ( empty( $list ) ) {
			return false;
		}

		$tags_key = sanitize_key( 'noptin_' . $this->slug . '_tags' );
		$tags     = get_post_meta( $campaign['campaign_id'], $tags_key, true );

		$extra_key = sanitize_key( 'noptin_' . $this->slug . '_extra' );
		$extra     = get_post_meta( $campaign['campaign_id'], $extra_key, true );

		$post     = get_post( $campaign['campaign_id'] );

		$campaign['custom_merge_tags']['unsubscribe_url'] = 'http://temporaryunsubscribe.com';

		$campaign_data = array(
			'merge_tags'    => $campaign['custom_merge_tags'],
			'email_body'    => $post->post_content,
			'email_subject' => get_the_title( $campaign['campaign_id'] ),
			'preview_text'  => get_post_meta( $campaign['campaign_id'], 'preview_text', true ),
		);

		if ( $this->supports( 'overide_footers' ) ) {
			$campaign_data['permission_text'] = '';
			$campaign_data['footer_text'] = '';
		}

		$strip_tags       = noptin()->mailer->strip_tags;

		noptin()->mailer->strip_tags = true;

		$campaign          = noptin()->mailer->prepare( $campaign_data );
		$campaign['tags']  = $tags;
		$campaign['extra'] = $extra;

		$campaign['email_body'] = str_replace( 'http://temporaryunsubscribe.com', $this->get_unsubscribe_tag(), $campaign['email_body'] );

		noptin()->mailer->strip_tags = $strip_tags;

		$list->send_campaign( $campaign );
	}

	/**
	 * Returns the unsubscription tag.
	 *
	 * @since 1.5.1
	 */
	public function get_unsubscribe_tag() {
		return '[[unsubscribe_url]]';
	}

	/**
	 * Registers list options.
	 *
	 * @since 1.5.1
	 * @param array $options
	 * @param string $slug
	 * @param Noptin_Abstract_Integration $integration
	 */
	public function add_list_options( $options, $slug, $integration ) {

		if ( $integration->integration_type == 'normal' || $integration->integration_type == 'ecommerce' ) {

			$via       = str_replace( '_form', '', $slug );
			$via      .= $integration->integration_type == 'ecommerce' ? '_checkout' : '';

			$option    = sanitize_text_field( "noptin_{$this->slug}_{$via}_default_list" );

			$options[ $option ]  = array(
				'el'          => 'select',
				'section'     => 'integrations',
				'label'       => sprintf(
					'%s %s',
					$this->name,
					$this->list_providers->get_name()
				),
				'restrict'    => sprintf(
					'%s && %s',
					$this->get_enable_integration_option_name(),
					$integration->get_enable_integration_option_name()
				),
				'options'     => $this->list_providers->get_dropdown_lists(),
				'description' => sprintf( __( 'New subscribers will be added to this %s', 'newsletter-optin-box' ), $this->list_providers->get_name() ),
				'placeholder' => sprintf( __( 'Select a %s', 'newsletter-optin-box' ), $this->list_providers->get_name() ),
			);

			if ( $this->supports( 'tags' ) ) {

				$option    = sanitize_text_field( "noptin_{$this->slug}_{$via}_default_tags" );

				$options[ $option ]  = array(
					'el'          => 'input',
					'section'     => 'integrations',
					'label'       => sprintf( __( '%s tags', 'newsletter-optin-box' ), $this->name ),
					'description' => __( 'Enter a comma separated list of tags to assign new suscribers.', 'newsletter-optin-box' ) ,
					'placeholder' => 'tag 1, tag 2',
					'restrict'    => sprintf(
						'%s && %s',
						$this->get_enable_integration_option_name(),
						$integration->get_enable_integration_option_name()
					),
				);

			}

			foreach ( $this->list_providers->get_secondary() as $secondary => $is_universal ) {

				$option    = sanitize_text_field( "noptin_{$this->slug}_{$via}_default_{$secondary}" );

				if ( $is_universal ) {

					$options[ $option ]  = array(
						'el'          => 'multiselect',
						'section'     => 'integrations',
						'label'       => sprintf(
							'%s %s',
							$this->name,
							$secondary
						),
						'restrict'    => sprintf(
							'%s && %s',
							$this->get_enable_integration_option_name(),
							$integration->get_enable_integration_option_name()
						),
						'options'     => $this->list_providers->get_dropdown( $secondary ),
						'description' => sprintf( __( 'New subscribers will be added to this %s', 'newsletter-optin-box' ), $secondary ),
					);

				} else {

					$options[ $option ]  = array(
						'el'          => 'input',
						'section'     => 'integrations',
						'label'       => sprintf(
							'%s %s',
							$this->name,
							$secondary
						),
						'description' => __( 'New subscribers will be added to here', 'newsletter-optin-box' ),
						'restrict'    => sprintf(
							'%s && %s',
							$this->get_enable_integration_option_name(),
							$integration->get_enable_integration_option_name()
						),
					);

				}

			}

		}

		return $options;
	}

}
