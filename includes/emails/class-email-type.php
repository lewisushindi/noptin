<?php
/**
 * Emails API: Email Type.
 *
 * Container for a single email type.
 *
 * @since   1.7.0
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Container for a single email type.
 *
 * @since 1.7.0
 * @internal
 * @ignore
 */
abstract class Noptin_Email_Type {

	/**
	 * @var string
	 */
	public $type; // newsletter, woocommerce_new_order, etc.

	/**
	 * True when email is being sent.
	 *
	 * @var bool
	 */
	public $sending = false;

	/**
	 * @var string Current unsubscribe URL.
	 */
	public $unsubscribe_url = '';

	/**
	 * @var \Hizzle\Noptin\DB\Subscriber
	 */
	public $subscriber;

	/**
	 * @var WP_User
	 */
	public $user;

	/**
	 * @var array Current recipient.
	 */
	public $recipient = array(); // Array containing campaign id, user id and subscriber id.

	/**
	 * Registers relevant hooks.
	 *
	 */
	public function add_hooks() {
		add_action( 'noptin_prepare_email_preview', array( $this, 'prepare_preview' ), 10, 3 );
		add_filter( 'noptin_get_email_prop', array( $this, 'maybe_set_default' ), 10, 3 );
		add_filter( 'noptin_get_default_email_props', array( $this, 'get_default_props' ), 10, 2 );
	}

	/**
	 * Sends a test email.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $campaign
	 * @param string $recipient
	 * @return bool Whether or not the test email was sent
	 */
	public function send_test( $campaign, $recipient ) {

		$recipient = sanitize_email( $recipient );

		$this->prepare_test_data( $campaign );

		// Maybe set related subscriber.
		$this->maybe_set_subscriber_and_user( $recipient );

		return $this->send( $campaign, 'test', array( $recipient => false ) );
	}

	/**
	 * Returns default email properties.
	 *
	 * @param array $props
	 * @param \Hizzle\Noptin\Emails\Email $email
	 * @return array
	 */
	public function get_default_props( $props, $email ) {

		if ( $email->type !== $this->type && $email->get_sub_type() !== $this->type ) {
			return $props;
		}

		$methods = get_class_methods( $this );

		if ( empty( $methods ) ) {
			return $props;
		}

		// Find all methods that begin with default_.
		foreach ( $methods as $method ) {

			if ( 0 !== strpos( $method, 'default_' ) ) {
				continue;
			}

			$prop = str_replace( 'default_', '', $method );

			$props[ $prop ] = call_user_func( array( $this, $method ), $email );
		}

		return $props;
	}

	/**
	 * Returns the default content.
	 *
	 */
	public function default_content_normal() {

		/**
		 * Filters the default email body
		 *
		 * @param string $body The default email body
		 */
		return apply_filters( "noptin_default_{$this->type}_body", '' );
	}

	/**
	 * Prepares the default blocks.
	 *
	 * @return string
	 */
	protected function prepare_default_blocks() {

		$normal = $this->default_content_normal();

		if ( ! empty( $normal ) ) {
			$normal = wpautop( $normal );

			// Ensure that shortcodes are not wrapped in paragraphs.
			$normal = shortcode_unautop( $normal );

			// Ensure that merge tags are not wrapped in paragraphs.
			$normal = Noptin_Email_Generator::merge_tags_unautop( $normal, true );

			// Convert paragraphs to blocks.
			return str_replace(
				array( '<p>', '</p>' ),
				array( '<!-- wp:paragraph --><p class="wp-block-paragraph" id="block-' . wp_generate_uuid4() . '">', '</p><!-- /wp:paragraph -->' ),
				$normal
			);
		}

		return '<!-- wp:paragraph --> <p class="wp-block-paragraph" id="block-' . wp_generate_uuid4() . '"></p> <!-- /wp:paragraph -->';
	}

	/**
	 * Returns the default content.
	 *
	 */
	public function default_content_visual() {

		$blocks  = $this->prepare_default_blocks();
		$footer  = get_noptin_footer_text();
		$footer  = '<!-- wp:paragraph {"style":{"noptin":{"typography":{"textAlign":"center","fontSize":13},"color":{"text":"#666666","link":"#111111"}}}} --> <p style="text-align:center;font-size:13px;color:#666666" class="wp-block-paragraph" id="footer-text">' . $footer . '</p> <!-- /wp:paragraph -->';
		$content = '<!-- wp:noptin/group {"style":{"noptin":{"color":{"background":"#ffffff"}}}} --> <div class="wp-block-noptin-group aligncenter" id="main-content-wrapper"><table width="600px" align="center" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:100%;border-collapse:separate;background-color:#ffffff"><tbody><tr><td class="noptin-block-group__inner" align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="background-color:#ffffff">' . $blocks . '</td></tr></tbody></table></td></tr></tbody></table></div> <!-- /wp:noptin/group --> <!-- wp:noptin/group --> <div class="wp-block-noptin-group aligncenter" id="main-footer-wrapper"><table width="600px" align="center" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:100%;border-collapse:separate"><tbody><tr><td class="noptin-block-group__inner" align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td>' . $footer . '</td></tr></tbody></table></td></tr></tbody></table></div> <!-- /wp:noptin/group -->';

		/**
		 * Filters the default email body
		 *
		 * @param string $body The default email body
		 */
		return apply_filters( "noptin_default_{$this->type}_body_visual", $content );
	}

	/**
	 * Sets the default value for a given email type's value.
	 *
	 * @param mixed $value
	 * @param string $prop
	 * @param Noptin_Automated_Email $email
	 */
	public function maybe_set_default( $value, $prop, $email ) {

		// Abort if the email is saved or is not our type.
		if ( ! empty( $value ) || $email->exists() || $email->type !== $this->type ) {
			return $value;
		}

		// Set default name, template, and footer texts.
		switch ( $prop ) {

			case 'footer_text':
				$value = get_noptin_footer_text();
				break;

			case 'template':
				$value = get_noptin_option( 'email_template', 'paste' );
				break;
		}

		// Is there a custom method to filter this prop?
		$method = sanitize_key( "default_$prop" );
		if ( is_callable( array( $this, $method ) ) ) {
			$value = $this->$method();
		}

		// Apply email type specific filter then return.
		return apply_filters( "noptin_{$this->type}_default_$prop", $value );
	}

	/**
	 * Retrieves an array of subscriber merge tags.
	 *
	 * @return array
	 */
	public function get_subscriber_merge_tags() {

		$tags = array();
		foreach ( get_noptin_subscriber_smart_tags() as $smart_tag => $field ) {

			if ( empty( $field['callback'] ) ) {
				$field['callback'] = array( $this, 'get_subscriber_field' );
			}

			$tags[ $smart_tag ] = $field;

		}

		return $tags;
	}

	/**
	 * Custom field value of the current subscriber.
	 *
	 * @param array $args
	 * @param string $field
	 * @return string
	 */
	public function get_subscriber_field( $args = array(), $field = 'first_name' ) {
		$field = strtolower( $field );

		// Abort if no subscriber.
		if ( empty( $this->subscriber ) ) {
			return '';
		}

		// Maybe convert to new subscriber object if we have the old one.
		$subscriber = noptin_get_subscriber( $this->subscriber );

		// Fetch the value.
		return $subscriber->get( $field );
	}

	/**
	 * Retrieves an array of user merge tags.
	 *
	 * @return array
	 */
	public function get_user_merge_tags() {

		return array(

			'user.id'           => array(
				'description' => __( "The user's ID", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.id',
			),

			'user.email'        => array(
				'description' => __( "The user's email address", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.email',
			),

			'user.login'        => array(
				'description' => __( "The user's login name", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.login',
			),

			'user.first_name'   => array(
				'description' => __( "The user's first name", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => "user.first_name default='Jane'",
			),

			'user.last_name'    => array(
				'description' => __( "The user's last name", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => "user.last_name default='Doe'",
			),

			'user.display_name' => array(
				'description' => __( "The user's display name", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => "user.display_name default='there'",
			),

			'user.description'  => array(
				'description' => __( "The user's description", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.description',
			),

			'user.url'          => array(
				'description' => __( "The user's website, if available", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.url',
			),

			'user.registered'   => array(
				'description' => __( "The user's registration date", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => 'user.registered',
			),

			'user.meta'         => array(
				'description' => __( "The user's meta field value", 'newsletter-optin-box' ),
				'callback'    => array( $this, 'get_user_field' ),
				'example'     => "user.meta key='xyz' default='123'",
			),

		);
	}

	/**
	 * Custom field value of the current User.
	 *
	 * @param array $args
	 * @param string $field
	 * @return string
	 */
	public function get_user_field( $args = array(), $field = 'user.first_name' ) {

		// Prepare vars.
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$field   = str_replace( 'user.', 'user_', strtolower( $field ) );

		// Standardize some fields.
		if ( 'user_id' === $field ) {
			$field = 'ID';
		}

		if ( in_array( $field, array( 'user_display_name' ), true ) ) {
			$field = str_replace( 'user_', '', $field );
		}

		if ( 'user_meta' === $field ) {

			if ( empty( $args['key'] ) ) {
				return esc_html( $default );
			}

			$field = trim( strtolower( $args['key'] ) );
		}

		// Abort if no user.
		if ( empty( $this->user ) || ! $this->user->has_prop( $field ) ) {
			return esc_html( $default );
		}

		return esc_html( (string) $this->user->get( $field ) );
	}

	/**
	 * Retrieves an array of supported merge tags.
	 *
	 * @return array
	 */
	public function get_merge_tags() {
		return array();
	}

	/**
	 * Retrieves flattened merge tags.
	 *
	 * @return array
	 */
	public function get_flattened_merge_tags( $existing = array() ) {

		if ( ! is_array( $existing ) ) {
			$existing = array();
		}

		$raw_tags   = $this->get_merge_tags();
		$merge_tags = array();
		foreach ( $raw_tags as $group => $_merge_tags ) {
			foreach ( $_merge_tags as $tag => $details ) {

				// Set missing categories.
				if ( empty( $details['group'] ) ) {
					$details['group'] = $group;
				}

				$merge_tags[ $tag ] = $details;
			}
		}

		return array_merge( $merge_tags, $existing );
	}

	/**
	 * Registers supported merge tags.
	 *
	 * @return array
	 */
	public function register_merge_tags() {

		// Register general merge tags.
		foreach ( $this->get_flattened_merge_tags() as $tag => $details ) {
			noptin()->emails->tags->add_tag( $tag, $details );
		}

		// Register subsriber merge tags.
		if ( ! empty( $this->subscriber ) ) {
			foreach ( $this->get_subscriber_merge_tags() as $tag => $details ) {
				noptin()->emails->tags->add_tag( $tag, $details );
			}
		}

		// Register user merge tags.
		if ( ! empty( $this->user ) ) {
			foreach ( $this->get_user_merge_tags() as $tag => $details ) {
				noptin()->emails->tags->add_tag( $tag, $details );
			}
		}

		// Unsubscribe URL.
		if ( ! empty( $this->unsubscribe_url ) ) {
			noptin()->emails->tags->tags['unsubscribe_url']['replacement'] = $this->unsubscribe_url;
		}
	}

	/**
	 * Unregisters supported merge tags.
	 *
	 * @return array
	 */
	public function unregister_merge_tags() {

		// Unregister general merge tags.
		foreach ( array_keys( $this->get_flattened_merge_tags() ) as $tag ) {
			noptin()->emails->tags->remove_tag( $tag );
		}

		// Unregister subsriber merge tags.
		if ( ! empty( $this->subscriber ) ) {
			foreach ( array_keys( $this->get_subscriber_merge_tags() ) as $tag ) {
				noptin()->emails->tags->remove_tag( $tag );
			}
		}

		// Unregister user merge tags.
		if ( ! empty( $this->user ) ) {
			foreach ( array_keys( $this->get_user_merge_tags() ) as $tag ) {
				noptin()->emails->tags->remove_tag( $tag );
			}
		}

		// Unsubscribe URL.
		if ( ! empty( $this->unsubscribe_url ) ) {
			noptin()->emails->tags->tags['unsubscribe_url']['replacement'] = '';
		}
	}

	/**
	 * Prepares an email preview.
	 *
	 * @param string $mode The preview mode. Either 'browser' or 'preview'.
	 * @param array $recipient The preview recipient.
	 * @param \Hizzle\Noptin\Emails\Email $email The email being previewed.
	 */
	public function prepare_preview( $mode, $recipient, $email ) {

		if ( $this->type !== $email->type && $this->type !== $email->get_sub_type() ) {
			return;
		}

		// Set subscriber.
		$this->subscriber = null;
		if ( isset( $recipient['sid'] ) && ! empty( $recipient['sid'] ) ) {
			$subscriber = noptin_get_subscriber( $recipient['sid'] );

			if ( $subscriber->exists() ) {
				$this->subscriber = $subscriber;
			}
		}

		// Set user.
		$this->user = null;
		if ( isset( $recipient['uid'] ) && ! empty( $recipient['uid'] ) ) {
			$user = get_userdata( $recipient['uid'] );

			if ( $user && $user->exists() ) {
				$this->user = $user;
			}
		}

		// If we have an email.
		if ( isset( $recipient['email'] ) && ! empty( $recipient['email'] ) ) {
			$this->maybe_set_subscriber_and_user( $recipient['email'] );
		}

		// Set-up test data for the preview.
		$this->prepare_test_data( $email );

		// Prepare enviroment.
		$this->before_send( $email );
	}

	/**
	 * Generates a preview email.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $campaign
	 * @return string
	 */
	public function generate_preview( $campaign ) {

		// Set-up test data for the preview.
		$this->prepare_test_data( $campaign );

		// Prepare enviroment.
		$this->before_send( $campaign );

		// Generate content.
		$content = noptin_generate_email_content( $campaign, $this->recipient, false );

		// Clean environment.
		$this->after_send( $campaign );

		if ( is_wp_error( $content ) ) {
			return $content->get_error_message();
		}

		// Filter and return.
		return apply_filters( 'noptin_generate_email_preview', $content, $campaign, $this );
	}

	/**
	 * Fired before sending a campaign.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $campaign
	 */
	protected function before_send( $campaign ) {

		// Prepare recipient.
		$this->recipient = array_filter(
			array(
				'cid' => $campaign->id,
				'uid' => empty( $this->user ) ? false : $this->user->ID,
				'sid' => empty( $this->subscriber ) ? false : $this->subscriber->get( 'id' ),
			)
		);

		// Generate unsubscribe url.
		$this->unsubscribe_url = get_noptin_action_url( 'unsubscribe', noptin_encrypt( wp_json_encode( $this->recipient ) ) );

		// Register merge tags.
		$this->register_merge_tags();

		// Indicate that we're sending an email.
		$this->sending = true;

		do_action( 'noptin_before_send_email', $campaign, $this );
	}

	/**
	 * Sends a notification.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $campaign
	 * @param string $key
	 * @param array|string $recipients
	 */
	public function send( $campaign, $key, $recipients ) {
		$result = false;

		// Prepare environment.
		$this->before_send( $campaign );

		// Prepare recipients.
		if ( is_string( $recipients ) ) {
			$recipients = array( $recipients => true );
		}

		// Send to each recipient.
		foreach ( $recipients as $email => $track ) {

			$GLOBALS['current_noptin_email'] = $email;

			// Send the email.
			$result = noptin_send_email(
				array(
					'recipients'               => $email,
					'subject'                  => noptin_parse_email_subject_tags( $campaign->get_subject() ),
					'message'                  => noptin_generate_email_content( $campaign, $this->recipient, $track ),
					'campaign_id'              => ! empty( $campaign->id ) ? $campaign->id : 0,
					'campaign'                 => $campaign,
					'headers'                  => array(),
					'attachments'              => array(),
					'reply_to'                 => '',
					'from_email'               => '',
					'from_name'                => '',
					'content_type'             => $campaign->get_email_type() === 'plain_text' ? 'text' : 'html',
					'unsubscribe_url'          => $this->unsubscribe_url,
					'disable_template_plugins' => ! ( $campaign->get_email_type() === 'normal' && $campaign->get_template() === 'default' ),
				)
			);
		}

		// Clear environment.
		$this->after_send( $campaign );

		// Log.
		if ( 'test' !== $key && ! $campaign->is_mass_mail() && ! empty( $campaign->id ) ) {
			increment_noptin_campaign_stat( $campaign->id, '_noptin_sends' );
		}

		return $result;
	}

	/**
	 * Fired after sending a campaign.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $campaign
	 */
	protected function after_send( $campaign ) {

		// Revert recipient.
		$this->recipient = array();

		// Indicate that we're nolonger sending an email.
		$this->sending = false;

		// Uregister merge tags.
		$this->unregister_merge_tags();

		$this->user            = null;
		$this->subscriber      = null;
		$this->unsubscribe_url = '';

		do_action( 'noptin_after_sending_email', $campaign, $this );
	}

	/**
	 * Prepares test data.
	 *
	 * @param Noptin_Automated_Email|Noptin_Newsletter_Email $email
	 */
	public function prepare_test_data( $email ) {

		// Set user.
		if ( empty( $this->user ) ) {
			$this->user = wp_get_current_user();
		}

		// Set subscriber.
		if ( empty( $this->subscriber ) ) {
			$subscriber = get_current_noptin_subscriber_id();

			if ( $subscriber ) {
				$this->subscriber = noptin_get_subscriber( $subscriber );
			}
		}

		do_action( 'noptin_prepare_test_data', $this, $email );
	}

	/**
	 * Sets subscriber and user for the email.
	 *
	 * @param string $email
	 */
	protected function maybe_set_subscriber_and_user( $email ) {

		if ( ! is_string( $email ) ) {
			return;
		}

		$email = sanitize_email( $email );

		if ( empty( $email ) ) {
			return;
		}

		// Set subscriber.
		$subscriber = noptin_get_subscriber( $email );

		if ( $subscriber->exists() ) {
			$this->subscriber = $subscriber;
		}

		// Set user.
		$user = get_user_by( 'email', $email );

		if ( $user ) {
			$this->user = $user;
		}
	}
}
