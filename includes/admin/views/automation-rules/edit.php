<?php

/**
 * Edit an automation
 */

defined( 'ABSPATH' ) || exit;

$rule = noptin_get_current_automation_rule();

if ( ! $rule->exists() && ! $rule->is_creating ) {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'Rule not found. It might have been deleted.', 'newsletter-optin-box' )
	);
	return;
}

$trigger = noptin()->automation_rules->get_trigger( $rule->trigger_id );
if ( empty( $trigger ) ) {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'Your website does not support that trigger.', 'newsletter-optin-box' )
	);
	return;
}

$rule_action  = noptin()->automation_rules->get_action( $rule->action_id );
if ( empty( $rule_action ) ) {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'Your website does not support that action.', 'newsletter-optin-box' )
	);
	return;
}

$trigger_settings = apply_filters( 'noptin_automation_rule_trigger_settings_' . $trigger->get_id(), $trigger->get_settings(), $rule, $trigger );
$action_settings  = apply_filters( 'noptin_automation_rule_action_settings_' . $rule_action->get_id(), $rule_action->get_settings(), $rule, $rule_action );

?>
<div class="wrap noptin-edit-automation-rule-page" id="noptin-wrapper">

	<h1 class="wp-heading-inline">
		<span><?php esc_html_e( 'Edit Automation Rule', 'newsletter-optin-box' ); ?></span>
		<a href="<?php echo esc_url( add_query_arg( 'noptin_create_automation_rule', '1', admin_url( 'admin.php?page=noptin-automation-rules' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'newsletter-optin-box' ); ?></a>
	</h1>

	<?php do_action( 'noptin_before_automation_rule_edit_page' ); ?>

	<div id="noptin-automation-rule-editor" class="edit-automation-rule noptin-fields">
		<form method="POST">

			<div class="noptin-automation-rule-editor-section">

				<hr>
				<h2 style="margin-bottom: 0.2em;"><?php esc_html_e( 'Trigger', 'newsletter-optin-box' ); ?></h2>
				<p class="description" style="margin-bottom: 16px;"><?php echo wp_kses_post( $trigger->get_description() ); ?></p>

				<?php foreach ( $trigger_settings as $setting_id => $args ) : ?>
					<?php Noptin_Vue::render_el( "trigger_settings['$setting_id']", $args ); ?>
				<?php endforeach; ?>

				<!-- Conditional logic -->
				<div v-if="hasConditions" class="noptin-text-wrapper field-wrapper">
					<label for="noptin-enable-conditional-logic" class="noptin-label"><?php esc_html_e( 'Conditional Logic', 'newsletter-optin-box' ); ?></label>
					<div class="noptin-content">

						<p class="description">
							<label>
								<input type="checkbox" id="noptin-enable-conditional-logic" v-model="conditional_logic.enabled" />
								<span style="font-weight: 400;"><?php esc_html_e( 'Optionally enable/disable this trigger depending on specific conditions.', 'newsletter-optin-box' ); ?></span>
							</label>
						</p>

						<div class="noptin-conditional-logic-wrapper card" v-show="conditional_logic.enabled">

							<p>
								<select v-model="conditional_logic.action">
									<option value="allow"><?php esc_html_e( 'Only run if', 'newsletter-optin-box' ); ?></option>
									<option value="prevent"><?php esc_html_e( 'Do not run if', 'newsletter-optin-box' ); ?></option>
								</select>

								<select v-model="conditional_logic.type">
									<option value="all"><?php esc_html_e( 'all', 'newsletter-optin-box' ); ?></option>
									<option value="any"><?php esc_html_e( 'any', 'newsletter-optin-box' ); ?></option>
								</select>

								<span>&nbsp;<?php esc_html_e( 'of the following rules are true:', 'newsletter-optin-box' ); ?>&nbsp;</span>
							</p>

							<p v-for="(rule, index) in conditional_logic.rules" class="noptin-conditional-logic-rule">

								<select class="noptin-condition-field" v-model="rule.type" @change="rule.value=getConditionPlaceholder(rule.type); rule.condition='is'">
									<option v-for="condition_type in availableConditions" :value="condition_type.key">{{ condition_type.label }}</option>
								</select>

								<select class="noptin-condition-comparison" v-model="rule.condition">
									<option
										v-for="(comparison_label, comparison_key) in getConditionalComparisonOptions( rule.type )"
										:value="comparison_key"
									>{{ comparison_label }}</option>
								</select>

								<select class="noptin-condition-value" v-model="rule.value" v-if="hasConditionOptions(rule.type) && 'is_empty' !== rule.condition && 'is_not_empty' !== rule.condition">
									<option value="" disabled><?php esc_html_e( 'Select a value', 'newsletter-optin-box' ); ?></option>
									<option v-for="(option_label, option_value) in getConditionOptions(rule.type)" :value="option_value">{{ option_label }}</option>
								</select>

								<input :type="getConditionInputType(rule.type)" class="noptin-condition-value" v-model="rule.value" :placeholder="getConditionPlaceholder(rule.type)" v-if="! hasConditionOptions(rule.type) && 'is_empty' !== rule.condition && 'is_not_empty' !== rule.condition"/>

								<a href="#" @click.prevent="removeConditionalLogicRule(rule)" class="noptin-remove-conditional-rule">
									<span class="dashicons dashicons-remove"></span>&nbsp;
								</a>

								<span v-if="! isLastConditionalLogicRule(index) && 'all' == conditional_logic.type">&nbsp;<?php esc_html_e( 'and', 'newsletter-optin-box' ); ?></span>
								<span v-if="! isLastConditionalLogicRule(index) && 'any' == conditional_logic.type">&nbsp;<?php esc_html_e( 'or', 'newsletter-optin-box' ); ?></span>

							</p>

							<p>
								<button class="button" @click.prevent="addConditionalLogicRule">
									<span class="dashicons dashicons-plus" style="vertical-align: middle;"></span>
									<span v-if="conditional_logic.rules && conditional_logic.rules.length"><?php esc_html_e( 'Add another rule', 'newsletter-optin-box' ); ?></span>
									<span v-else><?php esc_html_e( 'Add rule', 'newsletter-optin-box' ); ?></span>
								</button>
							</p>

						</div>
					</div>
				</div>
			</div>

			<div class="noptin-automation-rule-editor-section">
				<hr>
				<h2 style="margin-bottom: 0.2em;"><?php esc_html_e( 'Action', 'newsletter-optin-box' ); ?></h2>
				<p class="description" style="margin-bottom: 16px;"><?php echo wp_kses_post( $rule_action->get_description() ); ?></p>
					<?php

						foreach ( $action_settings as $setting_id => $args ) {

							if ( isset( $args['el'] ) && 'input' === $args['el'] ) {
								$args['append'] = '<a href="#TB_inline?width=0&height=550&inlineId=noptin-automation-rule-smart-tags" class="thickbox"><span class="dashicons dashicons-shortcode"></span></a>';
							}

							Noptin_Vue::render_el( "action_settings['$setting_id']", $args );
						}
					?>
			</div>

			<div>
				<input @click.prevent="saveRule" type="submit" class="button button-primary noptin-automation-rule-edit" value="<?php esc_attr_e( 'Save Changes', 'newsletter-optin-box' ); ?>">
				<span class="spinner save-automation-rule"></span>
			</div>

			<div class="noptin-save-saved" style="display:none"></div>
			<div class="noptin-save-error" style="display:none"></div>
		</form>

		<?php // Content for Thickboxes ?>
		<div id="noptin-automation-rule-smart-tags" v-if="availableSmartTags.length" style="display: none;">
			<?php require plugin_dir_path( __FILE__ ) . 'dynamic-content-tags.php'; ?>
		</div>
	</div>

	<?php do_action( 'noptin_after_automation_rule_edit_page' ); ?>

	<p class="description"><a href="<?php echo esc_attr( noptin_get_upsell_url( '/guide/automation-rules/', 'learn-more', 'automation-rules' ) ); ?>" target="_blank"><?php esc_html_e( 'Learn more about automation rules', 'newsletter-optin-box' ); ?></a></p>

</div>
