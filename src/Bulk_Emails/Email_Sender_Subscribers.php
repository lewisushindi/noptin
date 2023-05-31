<?php

namespace Hizzle\Noptin\Bulk_Emails;

/**
 * Bulk Emails API: Email Sender (subscribers).
 *
 * Contains the main email sender class.
 *
 * @since   1.12.0
 * @package Noptin
 */

defined( 'ABSPATH' ) || exit;

/**
 * The mass mailer class for sending emails to subscribers.
 */
class Email_Sender_Subscribers extends Email_Sender {

	/**
	 * The email sender.
	 * @var string
	 */
	protected $sender = 'noptin';

	/**
	 * Displays newsletter sending options.
	 *
	 * @param \Noptin_Newsletter_Email|\Noptin_automated_Email $campaign
	 *
	 * @return bool
	 */
	public function display_sending_options( $campaign ) {

		$current = $campaign->get( 'noptin_subscriber_options' );

		if ( empty( $current ) && ! defined( 'NOPTIN_ADDONS_PACK_VERSION' ) ) {
			?>
			<p><?php esc_html_e( 'The add-ons pack allows you to filter newsletter recipients by their subscription method, tags, lists, and custom fields.', 'newsletter-optin-box' ); ?></p>
			<p><a href="<?php echo esc_url( noptin_get_upsell_url( '/pricing/', 'filter-subscribers', 'email-campaigns' ) ); ?>" class="button noptin-button-standout" target="_blank"><?php esc_html_e( 'View Pricing', 'newsletter-optin-box' ); ?>&nbsp;<i class="dashicons dashicons-arrow-right-alt"></i></a></p>
			<?php
			return;
		}

		// Render sender options.
		$fields  = array();

		foreach ( get_noptin_subscriber_filters() as $key => $filter ) {
			$fields[ $key ] = array(
				'label'       => $filter['label'],
				'type'        => 'select',
				'options'     => array_replace(
					array(
						'' => __( 'Any', 'newsletter-optin-box' ),
					),
					$filter['options']
				),
				'description' => empty( $filter['description'] ) ? '' : $filter['description'],
			);
		}

		$fields = apply_filters( 'noptin_subscriber_sending_options', $fields, $campaign );

		$this->display_sending_fields( $campaign, 'noptin_subscriber_options', $fields );
	}

	/**
	 * Sends a single email to a subscriber.
	 *
	 * @param \Noptin_Newsletter_Email $campaign
	 * @param int|string $recipient
	 *
	 * @return bool
	 */
	public function send( $campaign, $recipient ) {

		// Fetch the subscriber.
		$subscriber = noptin_get_subscriber( $recipient );

		// Bail if the subscriber is not found or is unsubscribed...
		if ( ! $subscriber->exists() || ! $subscriber->is_active() ) {
			return null;
		}

		// ... or was already sent the email.
		if ( ! $this->can_email_subscriber( $campaign, $subscriber ) ) {
			return null;
		}

		// Generate and send the actual email.
		noptin()->emails->newsletter->subscriber = $subscriber;

		$result = noptin()->emails->newsletter->send( $campaign, $campaign->id, $subscriber->get_email() );

		// Log the send.
		update_noptin_subscriber_meta( $subscriber->get_id(), '_campaign_' . $campaign->id, (int) $result );

		return $result;
	}

	/**
	 * Checks if a subscriber is valid for a given task.
	 *
	 * @param \Noptin_Newsletter_Email $campaign The current campaign.
	 * @param \Hizzle\Noptin\DB\Subscriber $subscriber The subscriber to check.
	 * @return bool
	 */
	public function can_email_subscriber( $campaign, $subscriber ) {

		// Do not send twice.
		if ( '' !== get_noptin_subscriber_meta( $subscriber->get_id(), '_campaign_' . $campaign->id, true ) ) {
			return null;
		}

		// Prepare sender options.
		$options = $campaign->get( 'noptin_subscriber_options' );
		$options = is_array( $options ) ? $options : array();

		return apply_filters( 'noptin_subscribers_can_email_subscriber_for_campaign', true, $options, $subscriber->get_deprecated_subscriber(), $campaign );
	}

	/**
	 * Fired after a campaign is done sending.
	 *
	 * @param @param \Noptin_Newsletter_Email $campaign
	 *
	 */
	public function done_sending( $campaign ) {
		global $wpdb;

		$wpdb->delete(
			get_noptin_subscribers_meta_table_name(),
			array(
				'meta_key' => '_campaign_' . $campaign->id,
			)
		);

	}

	/**
	 * Get the next recipient for the campaign.
	 *
	 * @param \Noptin_Newsletter_Email $campaign
	 */
	public function get_recipients( $campaign ) {

		$manual_recipients = $campaign->get_manual_recipients_ids();
		if ( ! empty( $manual_recipients ) ) {
			return $manual_recipients;
		}

		// Prepare arguments.
		$args = array(
			'status'     => 'subscribed',
			'number'     => 5,
			'fields'     => 'id',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => '_campaign_' . $campaign->id,
					'compare' => 'NOT EXISTS',
				),
			),
		);

		// Handle custom fields.
		$options = $campaign->get( 'noptin_subscriber_options' );

		if ( is_array( $options ) ) {

			// Backward compatibility.
			if ( ! empty( $options['_subscriber_via'] ) ) {
				$args['source'] = $options['_subscriber_via'];
				unset( $options['_subscriber_via'] );
			}

			// Loop through the filters.
			foreach ( $options as $key => $value ) {
				if ( '' !== $value ) {
					$args[ $key ] = $value;
				}
			}
		}

		// (Backwards compatibility) Subscription source.
		$source = $campaign->get( '_subscriber_via' );

		if ( '' !== $source && empty( $options['source'] ) ) {
			$args['source'] = $source;
		}

		// Allow other plugins to filter the query.
		$args = apply_filters( 'noptin_mass_mailer_subscriber_query', $args, $campaign );

		// Run the query...
		return noptin_get_subscribers( $args );
	}

	/**
	 * Filters a recipient.
	 *
	 * @param false|array $recipient
	 * @param int $recipient_id
	 *
	 * @return array
	 */
	public function filter_recipient( $recipient, $recipient_id ) {
		$subscriber = noptin_get_subscriber( $recipient_id );

		if ( ! $subscriber->exists() ) {
			return $recipient;
		}

		return array(
			'name'  => $subscriber->get_name(),
			'email' => $subscriber->get_email(),
			'url'   => $subscriber->get_edit_url(),
		);
	}
}
