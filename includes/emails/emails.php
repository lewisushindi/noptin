<?php
/**
 * Emails API: functions.
 *
 * @since   1.7.0
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Sends an email.
 *
 * A true return value does not automatically mean that the user received the
 * email successfully. It just only means that the method used was able to
 * process the request without any errors.
 *
 * @param array $args An array of arguments.
 * @param array $args {
 *      An array of arguments to pass to the email sender.
 *
 *     @type string $unsubscribe_url         URL to unsubscribe from further emails.
 *     @type string $content_type            Content type of the email.
 *     @type string $from_name               Name of the sender.
 *     @type string $from_email              Email address of the sender.
 *     @type string $reply_to                Reply-to email address.
 *     @type string[] $attachments           Paths to files to attach.
 *     @type string[] $headers               Additional headers.
 *     @type string $message                 Email content.
 *     @type string $subject                 Email subject.
 *     @type string|string[]  $recipients    Array or comma-separated list of email recipients.
 *     @type bool  $disable_template_plugins Default 'true'. Whether or not to disable email template plugins.
 * }
 * @param bool $background Whether or not to send the email in the background.
 * @see Noptin_Email_Sender
 * @return bool|void Whether the email was sent successfully. Returns nothing if sending in the background.
 */
function noptin_send_email( $args, $background = false ) {

	if ( ! $background ) {
		return noptin()->emails->sender->send( $args );
	}

	return noptin()->emails->sender->bg_send( $args );
}

/**
 * Generates the content of an email.
 *
 * @param array $args An array of arguments.
 * @see Noptin_Email_Generator
 * @see get_noptin_email_types()
 * @return string|WP_Error
 */
function noptin_generate_email_content( $args ) {

	$generator = new Noptin_Email_Generator();
	return $generator->generate( $args );

}

/**
 * Generates the content of an automated email.
 *
 * @since 1.7.0
 * @param Noptin_Automated_Email $email
 * @param array $recipient
 * @param bool $track
 * @return string
 */
function noptin_generate_automated_email_content( $email, $recipient, $track = true ) {

	$args = array(
		'type'         => $email->get_email_type(), // normal, raw_html, plain_text
		'content'      => $email->get_content( $email->get_email_type() ),
		'template'     => $email->get_template(),
		'heading'      => $email->get( 'heading' ),
		'footer_text'  => $email->get( 'footer_text' ),
		'preview_text' => $email->get( 'preview_text' ),
		'campaign_id'  => $email->id,
		'track'        => $track,
		'recipient'    => $recipient,
	);

	return noptin_generate_email_content( $args );
}

/**
 * Converts HTML email to text email.
 *
 * @param string $html
 * @since 1.7.0
 * @return string
 */
function noptin_convert_html_to_text( $html ) {

	// Abort if DOMDocument not loaded.
	if ( ! class_exists( 'DOMDocument' ) ) {
		return strip_tags( $html );
	}

	try{
		return Noptin_HTML_Text::convert( $html );
	} catch( Exception $e ) {
		return strip_tags( $html );
	}

}

/**
 * Processes email subject tags.
 *
 * @since 1.7.0
 * @param string $subject
 * @return string
 */
function noptin_parse_email_subject_tags( $subject ) {
	return apply_filters( 'noptin_parse_email_subject_tags', $subject );
}

/**
 * Processes email content tags.
 *
 * @since 1.7.0
 * @param string $content
 * @return string
 */
function noptin_parse_email_content_tags( $content ) {
	return apply_filters( 'noptin_parse_email_content_tags', $content );
}

/**
 * Returns the URL to create a new automated email.
 *
 * @since 1.7.0
 * @return string
 */
function noptin_get_new_automation_url() {

	return add_query_arg(
		array(
			'page'        => 'noptin-email-campaigns',
			'section'     => 'automations',
			'sub_section' => 'new_campaign',
		),
		admin_url( '/admin.php' )
	);

}

/**
 * Returns an array of email senders.
 *
 * @since 1.5.2
 * @return array
 */
function get_noptin_email_senders() {
	return apply_filters(
		'noptin_email_senders',
		array(
			'noptin' => __( 'Noptin Subscribers', 'newsletter-optin-box' ),
		)
	);
}

/**
 * Returns the sender to use for a specific email.
 *
 * @since 1.5.2
 * @param int $campaign_id
 * @return array
 */
function get_noptin_email_sender( $campaign_id ) {
	$sender = get_post_meta( $campaign_id, 'email_sender', true );
	return in_array( $sender, array_keys( get_noptin_email_senders() ) ) ? $sender : 'noptin';
}

/**
 * Returns an array of email types.
 *
 * @since 1.7.0
 * @return array
 */
function get_noptin_email_types() {

	return apply_filters(
		'noptin_email_types',
		array(

			'normal'     => array(
				'label'       => __( 'Standard (Recommended)', 'newsletter-optin-box' ),
				'description' => __( 'Your email will be embedded inside a template', 'newsletter-optin-box' )
			),

			'plain_text' => array(
				'label'       => __( 'Plain Text', 'newsletter-optin-box' ),
				'description' => __( 'Sends a plain text email. It will contain no HTML which means open tracking and click tracking will not work.', 'newsletter-optin-box' )
			),

			'raw_html'   => array(
				'label'       => __( 'Raw HTML', 'newsletter-optin-box' ),
				'description' => __( "This is useful if you're using a drag and drop email builder. Please note that you should include an unsubscribe link by using the [[unsubscribe_url]] merge tag.", 'newsletter-optin-box' )
			),

		)
	);

}

/**
 * Returns an array of email templates.
 *
 * @since 1.7.0
 * @return array
 */
function get_noptin_email_templates() {

	$templates = array(
		'none'         => __( 'No template', 'newsletter-optin-box' ),
		'paste'        => __( 'Paste', 'newsletter-optin-box' ),
		'plain'        => __( 'Plain', 'newsletter-optin-box' ),
		'merriweather' => __( 'Merriweather', 'newsletter-optin-box' ),
		'default'      => __( 'Default', 'newsletter-optin-box' ),
	);

	return apply_filters( 'noptin_email_templates', $templates );
}

/**
 * Returns an array of email delay units.
 *
 * @since 1.7.0
 * @return array
 */
function get_noptin_email_delay_units() {

	$units = array(
		'minutes' => __( 'Minute(s)', 'newsletter-optin-box' ),
		'hours'   => __( 'Hour(s)', 'newsletter-optin-box' ),
		'days'    => __( 'Day(s)', 'newsletter-optin-box' ),
		'weeks'   => __( 'Week(s)', 'newsletter-optin-box' ),
		'months'  => __( 'Month(s)', 'newsletter-optin-box' ),
		'years'   => __( 'Year(s)', 'newsletter-optin-box' ),
	);

	return apply_filters( 'noptin_email_delay_units', $units );
}

/**
 * Returns the default footer text.
 *
 * @since 1.7.0
 * @return string
 */
function get_noptin_footer_text() {
	return get_noptin_option( 'footer_texts', noptin()->mailer->default_footer_text() );
}

/**
 * Returns the default permission text.
 *
 * @since 1.7.0
 * @return string
 */
function get_noptin_permission_text() {
	return get_noptin_option( 'permission_text', noptin()->mailer->default_permission_text() );
}
