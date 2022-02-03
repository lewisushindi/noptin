<?php
/**
 * Emails API: New post notifications.
 *
 * Notify users whenever you publish a new blog post.
 *
 * @since   1.7.0
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Notify users whenever you publish a new blog post.
 *
 * @since 1.7.0
 * @internal
 * @ignore
 */
class Noptin_New_Post_Notification extends Noptin_Automated_Email_Type {

	/**
	 * @var string
	 */
	public $type = 'new_post_notification';

	/**
	 * Retrieves the automated email type name.
	 *
	 */
	public function get_name() {
		return __( 'New Post Notification', 'newsletter-optin-box' );
	}

	/**
	 * Retrieves the automated email type description.
	 *
	 */
	public function get_description() {
		return __( 'Get more traffic to your site by notifying your subscribers every time you publish new content.', 'newsletter-optin-box' );
	}

	/**
	 * Retrieves the automated email type image.
	 *
	 */
	public function the_image() {
		echo '<svg xmlns="http://www.w3.org/2000/svg" fill="#ff5722" viewBox="0 0 122.88 122.83"><path d="M73.81,7.47A43.14,43.14,0,0,1,92.69,19.35a42.33,42.33,0,0,1,10.76,21.36l0,.28c.21,1.21.36,2.36.45,3.44.11,1.26.17,2.53.17,3.8h0V58.36c0,2.81,0,5.67.2,8.54a32.41,32.41,0,0,0,4.34,14.62A36.6,36.6,0,0,0,120,92.83a6.34,6.34,0,0,1,2.65,3.65,6.52,6.52,0,0,1-.08,3.56,6.62,6.62,0,0,1-1.91,3,6.33,6.33,0,0,1-4.25,1.57H82.27l0,.08h0c-4.14,24.2-37.61,24.13-41.65-.08H6.45A6.33,6.33,0,0,1,2,102.92a6.6,6.6,0,0,1-1.81-6.5A6.33,6.33,0,0,1,3,92.71c5.66-3.83,9.62-8,12.12-12.76s3.65-10.44,3.65-17.28V48.23c0-1.16.06-2.42.18-3.77s.29-2.52.51-3.76A42.89,42.89,0,0,1,49.39,7.41C54-2.47,69.2-2.49,73.81,7.47ZM87.71,24A36.34,36.34,0,0,0,70.38,13.57,3.42,3.42,0,0,1,68,11.22c-1.71-5.87-11-6-12.72-.05a3.43,3.43,0,0,1-2.48,2.38A36.1,36.1,0,0,0,26.15,41.9q-.28,1.58-.42,3.15c-.09,1-.13,2-.13,3.18V62.67c0,7.91-1.38,14.56-4.45,20.43-2.94,5.62-7.36,10.39-13.54,14.72H115.27A42.38,42.38,0,0,1,102.8,85,39.18,39.18,0,0,1,97.5,67.4c-.22-2.88-.21-6-.2-9V48.23h0c0-1.1,0-2.17-.13-3.22s-.21-2-.36-2.85l-.06-.27a35.62,35.62,0,0,0-9-17.9Z"/></svg>';
	}

	/**
	 * Returns the default subject.
	 *
	 */
	public function default_subject() {
		return '[[post_title]]';
	}

	/**
	 * Returns the default heading.
	 *
	 */
	public function default_heading() {
		return '[[post_title]]';
	}

	/**
	 * Returns the default content.
	 *
	 */
	public function default_content_normal() {
		ob_start();
		?>
		<p>[[post_excerpt]]</p>
		<p>[[button url="post_url" text="<?php esc_attr_e( 'Continue Reading', 'newsletter-optin-box' ); ?>"]]</p>
		<p><?php _e( "If that doesn't work, copy and paste the following link in your browser:", 'newsletter-optin-box' ); ?></p>
		<p>[[post_url]]</p>
		<p><?php _e( 'Cheers', 'newsletter-optin-box' ); ?></p>
		<p>[[post_author]]</p>
		<?php
		return ob_get_clean();
	}

	/**
	 * Returns the default plain text content.
	 *
	 */
	public function default_content_plain_text() {
		return noptin_convert_html_to_text( $this->default_content_normal() );
	}

	/**
	 * Displays a metabox.
	 *
	 * @param Noptin_Automated_Email $campaign
	 */
	public function render_metabox( $campaign ) {

		if ( defined( 'NOPTIN_WELCOME_EMAILS_FILE' ) ) {
			return;
		}

		$url = add_query_arg(
			array(
				'utm_medium'   => 'plugin-dashboard',
				'utm_campaign' => 'post-digests',
				'utm_source'   => 'email-editor',
			),
			'https://noptin.com/product/ultimate-addons-pack'
		);

		printf(
			'<p>%s</p><p>%s</p>',
			__( 'By default, this email will only send for new blog posts.', 'newsletter-optin-box' ),
			sprintf(
				__( 'Install the %s to send notifications for products and other post types or limit notifications to certain categories and tags.', 'newsletter-optin-box' ),
				"<a href='$url' target='_blank'>Ultimate Addons Pack</a>"
			)
		);

	}

}
