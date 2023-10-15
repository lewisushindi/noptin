<?php

namespace Hizzle\Noptin\Objects;

/**
 * Generic object trigger.
 *
 * @since 2.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Generic object trigger.
 */
class Trigger extends \Noptin_Abstract_Trigger {

	/**
	 * @var string $object_type The object type.
	 */
	public $object_type;

	/**
	 * @var string $trigger_id
	 */
	public $trigger_id;

	/**
	 * @var array $trigger_args
	 */
	public $trigger_args;

	/**
	 * Constructor.
	 *
	 * @param string $trigger_id The trigger id.
	 * @param array  $trigger_args The trigger args.
	 * @param Collection $collection The collection.
	 * @since 2.2.0
	 */
	public function __construct( $trigger_id, $trigger_args, $collection ) {
		$this->object_type  = $collection->type;
		$this->trigger_id   = $trigger_id;
		$this->trigger_args = $trigger_args;
		$this->category     = $collection->label;
		$this->integration  = $collection->integration;

		if ( empty( $trigger_args['subject'] ) ) {
			$this->is_user_based = true;
		}

		add_action( 'noptin_fire_object_trigger_' . $this->trigger_id, array( $this, 'fire_trigger' ) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_id() {
		return $this->trigger_id;
	}

	/**
	 * @inheritdoc
	 */
	public function get_name() {
		return $this->trigger_args['label'];
	}

	/**
	 * @inheritdoc
	 */
	public function get_description() {
		return $this->trigger_args['description'];
	}

	/**
	 * Returns an array of known smart tags.
	 *
	 * @since 2.2.0
	 * @return array
	 */
	public function get_known_smart_tags() {

		$args = array();

		// Add subject smart tags.
		if ( ! empty( $this->trigger_args['subject'] ) && $this->trigger_args['subject'] !== $this->object_type ) {
			$args = Store::smart_tags( $this->trigger_args['subject'], true );
		}

		// Add object args.
		$args = array_merge(
			$args,
			Store::smart_tags( $this->object_type, true )
		);

		// Add extra args.
		if ( ! empty( $this->trigger_args['extra_args'] ) ) {
			$args = array_merge(
				$args,
				Store::convert_fields_to_smart_tags(
					$this->trigger_args['extra_args'],
					$this->object_type,
					Store::get_collection_config( $this->object_type ),
					Store::get_collection_config( $this->object_type, 'smart_tags_prefix' )
				)
			);
		}

		// Add provided args.
		if ( ! empty( $this->trigger_args['provides'] ) ) {
			foreach ( noptin_parse_list( $this->trigger_args['provides'] ) as $object_type ) {
				$args = array_merge(
					$args,
					Store::smart_tags( $object_type, true )
				);
			}
		}

		// Add generic smart tags.
		return array_merge(
			$args,
			parent::get_known_smart_tags()
		);

	}

	/**
	 * Fires the trigger.
	 *
	 * @param array $args The trigger args.
	 * @since 2.2.0
	 */
	public function fire_trigger( $args ) {

		try {
			$subject = $this->prepare_current_objects( $args );
		} catch ( \Exception $e ) {
			return;
		}

		// Record activity.
		if ( ! empty( $args['url'] ) && ! empty( $args['email'] ) ) {
			noptin_record_subscriber_activity(
				$args['email'],
				trim(
					sprintf(
						'%s <a href="%s">view</a> %s',
						$this->get_name(),
						esc_url_raw( $args['url'] ),
						! empty( $args['activity'] ) ? ' - ' . $args['activity'] : ''
					)
				)
			);
		}

		$this->trigger( $subject, $args );

	}

	/**
	 * Fetches the correct subject.
	 *
	 * @param mixed $subject_id The subject ID.
	 * @since 2.2.0
	 * @return false|\WP_User|Record
	 */
	protected function get_collection_subject( $subject_id ) {

		if ( empty( $this->trigger_args['subject'] ) ) {
			return get_userdata( $subject_id );
		}

		$collection = Store::get( $this->trigger_args['subject'] );

		if ( empty( $collection ) ) {
			return false;
		}

		$subject = $collection->get( $subject_id );

		if ( empty( $subject ) || ! $subject->exists() ) {
			return false;
		}

		return $subject;
	}

	/**
	 * Serializes the trigger args.
	 *
	 * @since 2.2.0
	 * @param array $args The args.
	 * @return false|array
	 */
	public function serialize_trigger_args( $args ) {
		unset( $args['smart_tags'] );
		unset( $args['subject'] );
		return $args;
	}

	/**
	 * Unserializes the trigger args.
	 *
	 * @since 2.2.0
	 * @param array $args The args.
	 * @return array|false
	 */
	public function unserialize_trigger_args( $args ) {

		// Fetch person.
		$subject = $this->prepare_current_objects( $args );

		// Prepare args.
		$prepared = $this->prepare_trigger_args( $subject, $args );

		// Check for any changes that shouldn't be allowed.
		if ( ! empty( $args['unserialize'] ) ) {
			foreach ( $args['unserialize'] as $key => $original_value ) {
				$current_value = $prepared['smart_tags']->replace_in_text_field( '[[' . $key . ']]' );
				if ( noptin_clean( $original_value ) !== $current_value ) {
					throw new \Exception(
						sprintf(
							'%s changed from "%s" to "%s"',
							$key,
							$current_value,
							$original_value
						)
					);
				}
			}
		}

		return $prepared;
	}

	/**
	 * Prepares the object trigger args.
	 *
	 * @since 2.2.0
	 * @param array $args The args.
	 * @return array|false
	 */
	private function prepare_current_objects( $args ) {
		global $noptin_current_objects;

		// Make sure we have an array.
		$noptin_current_objects = array();

		// Fetch collection.
		$collection = Store::get( $this->object_type );

		if ( empty( $collection ) ) {
			throw new \Exception( 'Collection not registered' );
		}

		// Fetch person.
		$subject = $this->get_collection_subject( $args['subject_id'] );

		if ( empty( $subject ) ) {
			throw new \Exception( 'Subject not found' );
		}

		if ( ! empty( $this->trigger_args['subject'] ) ) {
			$noptin_current_objects[ $this->trigger_args['subject'] ] = $subject;
		}

		// Fetch object.
		$object = $collection->get( $args['object_id'] );

		if ( empty( $object ) || ! $object->exists() ) {
			throw new \Exception( $this->object_type . ' not found' );
		}

		$noptin_current_objects[ $this->object_type ] = $object;

		// Provided objects.
		if ( ! empty( $args['provides'] ) ) {
			foreach ( $args['provides'] as $object_type => $id ) {
				$collection = Store::get( $object_type );

				if ( empty( $collection ) ) {
					throw new \Exception( 'Collection not registered' );
				}

				$object = $collection->get( $id );

				if ( empty( $object ) || ! $object->exists() ) {
					throw new \Exception( $object_type . ' not found' );
				}

				$noptin_current_objects[ $object_type ] = $object;
			}
		}

		return $subject;
	}
}