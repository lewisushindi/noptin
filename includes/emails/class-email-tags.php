<?php
/**
 * Forms API: Dynamic Email Tags.
 *
 * Allows users to use dynamic tags in emails.
 *
 * @since   1.7.0
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Allows users to use dynamic tags in emails.
 *
 * @internal
 * @access private
 * @since 1.7.0
 * @ignore
 */
class Noptin_Email_Tags extends Noptin_Dynamic_Content_Tags {

	/**
	 * @var Noptin_Automation_Rules_Smart_Tags|null $smart_tags
	 */
	public $smart_tags = null;

	/**
	 * Register core hooks.
	 */
	public function add_hooks() {

		// Add hooks.
		add_action( 'init', array( $this, 'register' ), 0 );
		add_filter( 'noptin_parse_email_subject_tags', array( $this, 'replace_in_subject' ), 10, 2 );
		add_filter( 'noptin_parse_email_content_tags', array( $this, 'replace_in_body' ), 10, 2 );
	}

	/**
	 * @param string $string The string containing dynamic content tags.
	 * @param string $escape_function Escape mode for the replacement value. Leave empty for no escaping.
	 * @return string
	 */
	protected function replace( $content, $escape_function = '' ) {
		return $this->replace_with_brackets( $content, $escape_function );
	}

	/**
	 * Replaces in subject
	 *
	 * @param string $content
	 * @param bool $is_partial
	 * @return string
	 */
	public function replace_in_subject( $content, $is_partial = false ) {

		if ( ! empty( $this->smart_tags ) ) {
			$this->smart_tags->is_partial = $is_partial;
			$content                      = $this->smart_tags->replace_in_text_field( $content );
			$this->smart_tags->is_partial = false;
		}

		$this->is_partial = $is_partial;
		$result           = $this->replace( $content, 'strip_tags' );
		$this->is_partial = false;
		return $result;
	}

	/**
	 * Replaces in the email body
	 *
	 * @param string $content
	 * @param bool $is_partial
	 * @return string
	 */
	public function replace_in_body( $content, $is_partial = false ) {

		if ( ! empty( $this->smart_tags ) ) {
			$this->smart_tags->is_partial = $is_partial;
			$content                      = $this->smart_tags->replace_in_body( $content );
			$this->smart_tags->is_partial = false;
		}

		$this->is_partial = $is_partial;
		$content          = $this->replace( $content, '' );
		$this->is_partial = false;
		return $content;
	}

	/**
	 * Register template tags
	 */
	public function register() {
		/** @var \WP_Locale $wp_locale */
		global $wp_locale;

		$this->tags['unsubscribe_url'] = array(
			'description' => __( 'The unsubscribe URL.', 'newsletter-optin-box' ),
			'callback'    => '\Hizzle\Noptin\Emails\Main::get_current_unsubscribe_url',
		);

		$this->tags['blog_name'] = array(
			'description' => __( 'The website name.', 'newsletter-optin-box' ),
			'replacement' => get_bloginfo( 'name' ),
		);

		$this->tags['blog_description'] = array(
			'description' => __( 'The website description.', 'newsletter-optin-box' ),
			'replacement' => get_bloginfo( 'description' ),
		);

		$this->tags['home_url'] = array(
			'description' => __( 'The website URL.', 'newsletter-optin-box' ),
			'callback'    => 'home_url',
			'no_args'     => true,
		);

		$this->tags['date'] = array(
			'description' => __( 'The current date', 'newsletter-optin-box' ),
			'replacement' => date_i18n( get_option( 'date_format' ) ),
		);

		$this->tags['time'] = array(
			'description' => __( 'The current time', 'newsletter-optin-box' ),
			'replacement' => date_i18n( get_option( 'time_format' ) ),
		);

		$this->tags['year'] = array(
			'description' => __( 'The current year', 'newsletter-optin-box' ),
			'replacement' => date_i18n( 'Y' ),
		);

		$this->tags['month'] = array(
			'description'       => __( 'The current month', 'newsletter-optin-box' ),
			'replacement'       => current_time( 'm' ),
			'options'           => $wp_locale->month,
			'conditional_logic' => 'number',
		);

		$this->tags['day'] = array(
			'description'       => __( 'The day of the month', 'newsletter-optin-box' ),
			'replacement'       => current_time( 'j' ),
			'options'           => array_combine( range( 1, 31 ), range( 1, 31 ) ),
			'conditional_logic' => 'number',
		);

		$this->tags['weekday'] = array(
			'description'       => __( 'The day of the weekday', 'newsletter-optin-box' ),
			'replacement'       => current_time( 'w' ),
			'options'           => $wp_locale->weekday,
			'conditional_logic' => 'number',
		);

		$this->tags['noptin'] = array(
			'description' => __( 'Displays a personalized link to the Noptin website.', 'newsletter-optin-box' ),
			'callback'    => array( $this, 'noptin_url' ),
		);

		$this->tags['noptin_company'] = array(
			'description' => __( 'The company name that you set in Noptin > Settings > Emails.', 'newsletter-optin-box' ),
			'callback'    => array( $this, 'noptin_company' ),
		);

		$this->tags['subscriber_count'] = array(
			'description' => __( 'Replaced with the total number of subscribers', 'newsletter-optin-box' ),
			'callback'    => 'get_noptin_subscribers_count',
		);

		$this->tags['rule'] = array(
			'description' => __( 'Displays a horizontal rule', 'newsletter-optin-box' ),
			'callback'    => array( $this, 'get_horizontal_rule' ),
			'example'     => "rule height='3px' color='black' width='100%' margin='50px'",
		);

		$this->tags['spacer'] = array(
			'description' => __( 'Adds a blank vertical space', 'newsletter-optin-box' ),
			'callback'    => array( $this, 'get_spacer' ),
			'example'     => "spacer height='50px'",
		);

		$this->tags['button'] = array(
			'description' => __( 'Displays a button', 'newsletter-optin-box' ),
			'callback'    => array( $this, 'get_button' ),
			'example'     => "button text='Click Here' url='" . home_url() . "' background='brand' color='white' rounding='4px'",
		);
	}

	/**
	 * Returns a horizontal rule
	 *
	 * @param array $args
	 * @return string
	 */
	public function get_horizontal_rule( $args = array() ) {
		$height = isset( $args['height'] ) ? $args['height'] : '3px';
		$color  = isset( $args['color'] ) ? $args['color'] : '#454545';
		$width  = isset( $args['width'] ) ? $args['width'] : '100%';
		$margin = isset( $args['margin'] ) ? $args['margin'] : '50px';

		return sprintf(
			'<hr style="border-width: 0; background: %s; color: %s; height:%s; width:%s; margin:%s auto;">',
			esc_attr( $color ),
			esc_attr( $color ),
			esc_attr( $height ),
			esc_attr( $width ),
			esc_attr( $margin )
		);
	}

	/**
	 * Returns a spacer
	 *
	 * @param array $args
	 * @return string
	 */
	public function get_spacer( $args = array() ) {
		$spacer = isset( $args['height'] ) ? $args['height'] : '50px';
		return sprintf( "<div style='line-height:%s;height:%s;'>&#8202;</div>", esc_attr( $spacer ), esc_attr( $spacer ) );
	}

	/**
	 * Returns a button
	 *
	 * @param array $args
	 * @return string
	 */
	public function get_button( $args = array() ) {
		$url        = isset( $args['url'] ) ? $args['url'] : home_url();
		$background = isset( $args['background'] ) ? $args['background'] : 'brand';
		$color      = isset( $args['color'] ) ? $args['color'] : 'white';
		$rounding   = isset( $args['rounding'] ) ? $args['rounding'] : '4px';
		$text       = isset( $args['text'] ) ? $args['text'] : 'Click Here';

		if ( 'brand' === $background ) {
			$brand_color = get_noptin_option( 'brand_color' );
			$background  = empty( $brand_color ) ? '#1a82e2' : $brand_color;
		}

		// Generate button.
		$button = sprintf(
			'<a href="%s" style="background: %s; border: none; text-decoration: none; padding: 15px 25px; color: %s; border-radius: %s; display:inline-block; mso-padding-alt:0;text-underline-color:%s"><span style="mso-text-raise:15pt;">%s</span></a>',
			esc_attr( $url ), // Use esc_attr instead of esc_url to allow for merge tags.
			esc_attr( $background ),
			esc_attr( $color ),
			esc_attr( $rounding ),
			esc_attr( $background ),
			esc_html( $text )
		);

		return $this->center( $button );
	}

	/**
	 * Centers content.
	 *
	 * @param array $args
	 * @return string
	 */
	public function center( $content ) {

		ob_start();
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" style="padding: 12px;">
					<div style='text-align: center; padding: 20px;' align='center'>
						<?php echo wp_kses_post( $content ); ?>
					</div>
				</td>
			</tr>
		</table>
		<?php
			return ob_get_clean();
	}

	/**
	 * Noptin URL
	 *
	 * @return string
	 */
	public function noptin_url() {

		return sprintf(
			'<a target="_blank" href="%s">Noptin</a>',
			noptin_get_upsell_url( 'https://noptin.com/', 'powered-by', 'email-campaigns' )
		);
	}

	/**
	 * Noptin company
	 *
	 * @return string
	 */
	public function noptin_company() {
		return get_noptin_option( 'company', '' );
	}
}
