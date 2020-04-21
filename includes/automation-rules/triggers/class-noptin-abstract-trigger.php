<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' )  ) {
	die;
}

/**
 * Base triggers class.
 *
 * @since       1.2.8
 */
abstract class Noptin_Abstract_Trigger {
    
    /**
     * @var array
     */
    protected $rules = null;

    /**
     * Constructor.
     *
     * @since 1.2.8
     * @return string
     */
    public function __construct() {}
    
    /**
     * Retrieve the trigger's unique id.
     *
     * Only alphanumerics, dashes and underscrores are allowed.
     *
     * @since 1.2.8
     * @return string
     */
    public abstract function get_id();

    /**
     * Retrieve the trigger's name.
     *
     * @since 1.2.8
     * @return string
     */
    public abstract function get_name();

    /**
     * Retrieve the trigger's description.
     *
     * @since 1.2.8
     * @return string
     */
    public abstract function get_description();

    /**
     * Retrieve the trigger's image.
     *
     * @since 1.2.8
     * @return string
     */
    public function get_image() {
        return '';
    }

    /**
     * Retrieve the trigger's keywords.
     *
     * @since 1.2.8
     * @return array
     */
    public function get_keywords() {
        return array();
    }

    /**
     * Retrieve the trigger's settings.
     *
     * @since 1.2.8
     * @return array
     */
    public abstract function get_settings();

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

        $table = noptin()->automation_rules->get_table();
        $this->rules = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE `trigger_id`=%s `status`='1'"
            )
        );

        return $this->rules;
    }

    /**
     * Checks if there are rules for this trigger.
     *
     * @since 1.2.8
     * @return array
     */
    public function has_rules() {
        return ! empty( $this->get_rules() );
    }

    /**
     * Checks if this rule is valid for the above parameters.
     *
     * @since 1.2.8
     * @param Noptin_Automation_Rule $rule The rule to check for.
     * @param mixed $args Extra args for the action.
     * @param Noptin_Subscriber $subscriber The subscriber that this rule was triggered for.
     * @param Noptin_Abstract_Action $action The action to run.
     * @return bool
     */
    public function is_rule_valid_for_args( $rule, $args, $subscriber, $action ) {
        return true;
    }

    /**
     * Triggers action callbacks.
     *
     * @since 1.2.8
     * @param Noptin_Subscriber $subscriber The subscriber.
     * @param array $args Extra arguments passed to the action.
     * @return void
     */
    public function trigger( $subscriber, $args ) {

        foreach ( $this->get_rules() as $rule ) {

            // Retrieve the action.
            $action = noptin()->automation_rules->get_action( $rule->action );
            if ( empty( $action ) ) {
                continue;
            }

            // Prepare the rule.
            $rule   = noptin()->automation_rules->prepare_rule( $rule );

            // Ensure that the rule is valid for the provided args.
            if ( $this->is_rule_valid_for_args( $rule, $args, $subscriber, $action ) ) {
                $action->run( $subscriber, $rule, $args );
            }

        }

    }

}
