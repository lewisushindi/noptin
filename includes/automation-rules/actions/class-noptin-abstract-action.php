<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Base actions class.
 *
 * @since       1.2.8
 */
abstract class Noptin_Abstract_Action {

	/**
	 * @var array
	 */
	protected $rules = null;

	/**
	 * @var bool
	 */
	public $depricated = false;

	/**
	 * Retrieve the action's unique id.
	 *
	 * Only alphanumerics, dashes and underscrores are allowed.
	 * Maximum 255 characters.
	 *
	 * @since 1.2.8
	 * @return string
	 */
	abstract public function get_id();

	/**
	 * Retrieve the action's name.
	 *
	 * @since 1.2.8
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Retrieve the action's description.
	 *
	 * @since 1.2.8
	 * @return string
	 */
	abstract public function get_description();

	/**
	 * Retrieve the actions's rule description.
	 *
	 * @since 1.3.0
	 * @param Noptin_Automation_Rule $rule
	 * @return array
	 */
	public function get_rule_description( $rule ) {
		return $this->get_description();
	}

	/**
	 * Retrieve the actions's rule table description.
	 *
	 * @since 1.11.9
	 * @param Noptin_Automation_Rule $rule
	 * @return array
	 */
	public function get_rule_table_description( $rule ) {
		return '';
	}

	/**
	 * Groups rule action into a single string.
	 *
	 * @since 1.11.9
	 * @param array $meta
	 * @param Noptin_Automation_Rule $rule
	 * @return string
	 */
	public function rule_action_meta( $meta, $rule ) {
		$prepared = array();

		$meta = apply_filters( 'noptin_rule_action_meta_' . $this->get_id(), $meta, $rule, $this );
		$meta = apply_filters( 'noptin_rule_action_meta', $meta, $rule, $this );

		foreach ( $meta as $key => $value ) {
			if ( '' !== $value && false !== $value ) {
				$prepared[] = sprintf(
					'<span class="noptin-rule-action-meta"><span class="noptin-rule-action-meta-key">%s</span>: <span class="noptin-rule-action-meta-value">%s</span></span>',
					$key,
					$value
				);
			}
		}

		return wp_kses_post( implode( '', $prepared ) );
	}

	/**
	 * Retrieve the action's image.
	 *
	 * @since 1.2.8
	 * @return string
	 */
	public function get_image() {
		return plugin_dir_url( Noptin::$file ) . 'includes/assets/images/logo.png';
	}

	/**
	 * Retrieve the action's keywords.
	 *
	 * @since 1.2.8
	 * @return array
	 */
	public function get_keywords() {
		return array();
	}

	/**
	 * Retrieve the action's settings.
	 *
	 * @since 1.2.8
	 * @return array
	 */
	public function get_settings() {
		return array();
	}

	/**
	 * Returns all active rules attached to this action.
	 *
	 * @since 1.2.8
	 * @return array
	 */
	public function get_rules() {
		global $wpdb;

		if ( is_array( $this->rules ) ) {
			return $this->rules;
		}

		$this->rules = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}noptin_automation_rules WHERE `action_id`=%s AND `status`='1'",
				$this->get_id()
			)
		);

		foreach ( $this->rules as $rule ) {
			wp_cache_set( $rule->id, $rule, 'noptin_automation_rules', 10 );
		}

		return $this->rules;
	}

	/**
	 * Checks if there are rules for this trigger.
	 *
	 * @since 1.2.8
	 * @return array
	 */
	public function has_rules() {
		$rules = $this->get_rules();
		return ! empty( $rules );
	}

	/**
	 * Returns the subject's email address.
	 *
	 * @since 1.9.0
	 * @param mixed $subject The subject.
	 * @param Noptin_Automation_Rule $rule The automation rule used to trigger the action.
	 * @param array $args Extra arguments passed to the action.
	 * @return false|string
	 */
	public function get_subject_email( $subject, $rule, $args ) {

		// Provided via the email settings.
		if ( ! empty( $rule->action_settings['email'] ) ) {
			return $args['smart_tags']->replace_in_email( $rule->action_settings['email'] );
		}

		// Maybe fetch from the subscriber.
		if ( $subject instanceof Noptin_Subscriber ) {
			return $subject->email;
		}

		// ... or users.
		if ( $subject instanceof WP_User ) {
			return $subject->user_email;
		}

		// ... or the email argument.
		if ( ! empty( $args['email'] ) ) {
			return $args['email'];
		}

		return false;
	}

	/**
	 * Returns whether or not the action can run (dependancies are installed).
	 *
	 * @since 1.2.8
	 * @param mixed $subject The subject.
	 * @param Noptin_Automation_Rule $rule The automation rule that triggered the action.
	 * @param array $args Extra arguments passed to the action.
	 * @return bool
	 */
	public function can_run( $subject, $rule, $args ) {
		return true;
	}

	/**
	 * (Maybe) run the action.
	 *
	 * @since 1.3.0
	 * @param mixed $subject The subject.
	 * @param Noptin_Automation_Rule $rule The automation rule that triggered the action.
	 * @param array $args Extra arguments passed to the action.
	 */
	public function maybe_run( $subject, $rule, $args ) {

		// Ensure that we can run the action.
		if ( ! $this->can_run( $subject, $rule, $args ) ) {
			return;
		}

		// Run the action.
		$this->run( $subject, $rule, $args );

		// Update the run counts.
		$times_run = (int) $rule->times_run + 1;
		noptin()->automation_rules->update_rule( $rule, compact( 'times_run' ) );

		// Record activity.
		$subject_email = $this->get_subject_email( $subject, $rule, $args );
		if ( is_email( $subject_email ) ) {
			$action  = $this->get_name();
			$trigger = noptin()->automation_rules->get_trigger( $rule->trigger_id );
			$trigger = $trigger ? $trigger->get_name() : $rule->trigger_id;

			noptin_record_subscriber_activity(
				$subject_email,
				sprintf(
					// translators: %1 is the trigger, %2 is the action.
					__( 'Excecuted automation rule, Trigger: %1$s, Action: %2$s.', 'newsletter-optin-box' ),
					'<code>' . esc_html( $trigger ) . '</code>',
					'<code>' . esc_html( $action ) . '</code>'
				)
			);
		}
	}

	/**
	 * Runs the action.
	 *
	 * @since 1.2.8
	 * @param mixed $subject The subject.
	 * @param Noptin_Automation_Rule $rule The automation rule that triggered the action.
	 * @param array $args Extra arguments passed to the action.
	 * @return void
	 */
	abstract public function run( $subject, $rule, $args );

}
