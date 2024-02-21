<?php

/**
 * Container for a collection of people.
 *
 * @since   1.0.0
 */

namespace Hizzle\Noptin\Objects;

defined( 'ABSPATH' ) || exit;

/**
 * Container for a collection of people.
 */
abstract class People extends Collection {

	/**
	 * @var string object type.
	 */
	public $object_type = 'person';

	/**
	 * @var string The email sender for this collection.
	 */
	public $email_sender;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( ! empty( $this->email_sender ) ) {
			add_action( 'noptin_init_current_email_recipient', array( $this, 'prepare_test_recipient_data' ) );
		}

		parent::__construct();
	}

	/**
	 * Retrieves a single person from a WordPress user.
	 *
	 * @param \WP_User $user The user.
	 * @return Person $person The person.
	 */
	public function get_from_user( $user ) {
		return $this->get_from_email( $user->user_email );
	}

	/**
	 * Retrieves a single person from an email address.
	 *
	 * @param string $email The email address.
	 * @return Person $person The person.
	 */
	abstract public function get_from_email( $email );

	/**
	 * Retrieves fields that can be calculated from an email address.
	 */
	public function provides() {
		return array();
	}

	/**
	 * Prepares test data.
	 *
	 * @param \Hizzle\Noptin\Emails\Email $email
	 */
	public function prepare_test_data( $email ) {
		$recipient = \Hizzle\Noptin\Emails\Main::$current_email_recipient;

		// Abort if not test mode.
		if ( ! $email || ! $email->is_mass_mail() || $email->get_sender() !== $this->email_sender || ! empty( $recipient[ $this->type ] ) || ! isset( $recipient['mode'] ) || 'preview' !== $recipient['mode'] ) {
			return;
		}

		if ( 'noptin' !== $this->email_sender && ! noptin_has_active_license_key() ) {
			return;
		}

		$manual = $email->get_manual_recipients_ids();

		if ( ! empty( $manual ) ) {
			\Hizzle\Noptin\Emails\Main::$current_email_recipient[ $this->type ] = $manual[0];
		} else {
			\Hizzle\Noptin\Emails\Main::$current_email_recipient[ $this->type ] = $this->get_test_id();
		}
	}

	/**
	 * Retrieves a test ID.
	 *
	 */
	abstract public function get_test_id();
}
