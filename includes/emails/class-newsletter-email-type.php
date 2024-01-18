<?php
/**
 * Emails API: Newsletter Email Type.
 *
 * Container for the newsletter email type.
 *
 * @since   1.7.0
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Container for the newsletter email type.
 *
 * @since 1.7.0
 * @internal
 * @ignore
 */
class Noptin_Newsletter_Email_Type extends Noptin_Email_Type {

	/**
	 * @var string
	 */
	public $type = 'newsletter';

	/**
	 * Registers relevant hooks.
	 *
	 */
	public function add_hooks() {

        // Register parent hooks.
		parent::add_hooks();

        // Send newsletter emails.
		add_action( 'transition_post_status', array( $this, 'maybe_send_campaign' ), 100, 3 );
	}

	/**
	 * Returns the default campaign name.
	 */
	public function default_name() {

		$name = sprintf(
			// Translators: %s is the current date.
			__( 'Newsletter - %s', 'newsletter-optin-box' ),
			date_i18n( get_option( 'date_format' ) )
		);

		/**
		 * Filters the default newsletter name
		 *
		 * @param string $name The default newsletter name
		 */
		return apply_filters( 'noptin_default_newsletter_name', $name );
	}

	/**
	 * Returns the default plain text content.
	 *
	 */
	public function default_content_plain_text() {
		return noptin_convert_html_to_text( $this->default_content_normal() );
	}

	/**
	 *  (Maybe) Sends a newsletter campaign.
	 *
	 * @param string  $new_status The new campaign status.
	 * @param string  $old_status The old campaign status.
	 * @param WP_Post $post The new campaign post object.
	 */
	public function maybe_send_campaign( $new_status, $old_status, $post ) {

		// Maybe abort early.
		if ( 'publish' !== $new_status || 'publish' === $old_status ) {
			return;
		}

		// Ensure this is a newsletter campaign.
		if ( 'noptin-campaign' === $post->post_type && get_post_meta( $post->ID, 'campaign_type', true ) === $this->type ) {
			$this->send_campaign( $post );
		}
	}

	/**
	 * Sends a newsletter campaign.
	 *
	 * @param WP_Post $post The new campaign post object.
	 */
	public function send_campaign( $post ) {

		// Prepare campaign.
		$campaign = new Noptin_Newsletter_Email( $post->ID );

		// Abort if the campaign is not ready to be sent.
		if ( ! $campaign->can_send() ) {
			return;
		}

		// Log the campaign.
		log_noptin_message(
			sprintf(
				// Translators: %s is the campaign title.
				__( 'Sending the campaign: "%s"', 'newsletter-optin-box' ),
				esc_html( $post->post_title )
			)
		);

		// Send the campaign.
		$sender = $campaign->get_sender();

		if ( noptin()->bulk_emails()->has_sender( $sender ) ) {
			noptin()->bulk_emails()->send_pending();
		} else {
			do_action( 'noptin_send_email_via_' . $campaign->get_sender(), $campaign, null );
		}
	}
}
