<?php defined( 'ABSPATH' ) || exit; ?>
<?php

	$trigger     = defined( 'IS_NOPTIN_PREVIEW' ) ? 'immeadiate' : esc_attr( $triggerPopup );
	$after_click = esc_attr( $cssClassOfClick );
	$on_scroll   = esc_attr( $scrollDepthPercentage );
	$delay       = esc_attr( $timeDelayDuration );
	$class       = esc_attr( "noptin-slide-from-$slideDirection" );
	$seconds     = apply_filters( 'noptin_display_form_every_x_seconds', WEEK_IN_SECONDS, $form );
	$styles      = array(
		'background-color' => $noptinFormBg,
		'background-image' => "url('$noptinFormBgImg')",
		'width'            => $formWidth,
		'min-height'       => $formHeight,
		'color'            => $descriptionColor,
	);

	if ( 'popup' !== $optinType && 'slide_in' !== $optinType ) {
		$styles['width'] = '100%';
	}

	if ( is_numeric( $styles['width'] ) ) {
		$styles['width'] = $styles['width'] . 'px';
	}

	if ( is_numeric( $styles['min-height'] ) ) {
		$styles['min-height'] = $styles['min-height'] . 'px';
	}

	if ( empty( $noptinFormBgImg ) ) {
		unset( $styles['background-image'] );
	}

	$wrapper_styles = '';
	foreach ( $styles as $prop => $val ) {
		$val             = esc_attr( $val );
		$wrapper_styles .= " $prop:$val;";
	}

	foreach ( noptin_parse_list( 'formBorder prefixAdvanced prefixTypography noteAdvanced noteTypography descriptionAdvanced descriptionTypography titleAdvanced titleTypography noteAdvanced noteTypography' ) as $_autogenerated_prop ) {

		if ( empty( ${$_autogenerated_prop}['generated'] ) ) {
			${$_autogenerated_prop}['generated'] = '';
		}
	}

	$wrapper_styles .= $formBorder['generated'];

?>
<?php do_action( 'before_output_legacy_noptin_form', $form ); ?>
<div style='<?php echo esc_attr( $wrapper_styles ); ?>' data-trigger='<?php echo esc_attr( $trigger ); ?>' data-after-click='<?php echo esc_attr( $after_click ); ?>' data-on-scroll='<?php echo esc_attr( $on_scroll ); ?>' data-after-delay='<?php echo esc_attr( $delay ); ?>' data-hide-seconds='<?php echo esc_attr( $seconds * 1000 ); ?>' class='noptin-optin-form-wrapper <?php echo esc_attr( $class ); ?>'>
	<form class="noptin-optin-form <?php echo $singleLine ? 'noptin-form-single-line' : 'noptin-form-new-line'; ?>" <?php do_action( 'noptin_frontend_optin_form_attrs', $form ); ?>>

		<div class="noptin-form-header <?php echo ! empty( $image ) ? esc_attr( $imagePos ) : 'no-image'; ?>">

			<div class="noptin-form-header-text">

				<?php if ( ! $hidePrefix ) : ?>
					<div style="color:<?php echo esc_attr( $prefixColor ); ?>;<?php echo esc_attr( $prefixTypography['generated'] ); ?><?php echo esc_attr( $prefixAdvanced['generated'] ); ?>" class="noptin-form-prefix"><?php echo wp_kses_post( do_shortcode( $prefix ) ); ?></div>
				<?php endif; ?>

				<?php if ( ! $hideTitle ) : ?>
					<div style="color:<?php echo esc_attr( $titleColor ); ?>;<?php echo esc_attr( $titleTypography['generated'] ); ?><?php echo esc_attr( $titleAdvanced['generated'] ); ?>" class="noptin-form-heading"><?php echo wp_kses_post( do_shortcode( $title ) ); ?></div>
				<?php endif; ?>

				<?php if ( ! $hideDescription ) : ?>
					<div style="color:<?php echo esc_attr( $descriptionColor ); ?>;<?php echo esc_attr( $descriptionTypography['generated'] ); ?><?php echo esc_attr( $descriptionAdvanced['generated'] ); ?>" class="noptin-form-description"><?php echo wp_kses_post( do_shortcode( $description ) ); ?></div>
				<?php endif; ?>

			</div>

			<?php if ( ! empty( $image ) ) : ?>
				<div class="noptin-form-header-image">
					<img src="<?php echo esc_url( $image ); ?>" />
				</div>
			<?php endif; ?>

		</div>

		<div class="noptin-form-footer">

			<?php if ( ! $hideFields ) : ?>
				<div class="noptin-form-fields">

					<?php foreach ( $fields as $field ) : ?>
						<div class="noptin-optin-field-wrapper noptin-optin-field-wrapper-<?php echo esc_attr( $field['type']['type'] ); ?>">
							<?php do_action( 'noptin_field_type_frontend_optin_markup', $field, $data ); ?>
						</div>
					<?php endforeach; ?>

					<?php if ( $gdprCheckbox && ! $singleLine ) : ?>
						<div class="noptin-gdpr-checkbox-wrapper" style="margin-bottom: 10px;">
							<label><input type='checkbox' value='1' name='noptin_gdpr_checkbox' required="required"/><span><?php echo wp_kses_post( $gdprConsentText ); ?></span></label>
						</div>
					<?php endif; ?>

					<input type="hidden" name="noptin_form_id" value="<?php echo esc_attr( $id ); ?>" />

					<?php do_action( 'before_print_noptin_submit_button', $singleLine, $id ); ?>
					<input
						value="<?php echo esc_attr( $noptinButtonLabel ); ?>"
						type="submit"
						style="background-color: <?php echo esc_attr( $noptinButtonBg ); ?>; color: <?php echo esc_attr( $noptinButtonColor ); ?>;"
						class="noptin-form-submit <?php echo $singleLine ? '' : esc_attr( 'noptin-form-button-' . $buttonPosition ); ?>" />
				</div>
				<?php do_action( 'after_print_noptin_form_fields', $singleLine, $id ); ?>
			<?php endif; ?>

			<?php if ( $gdprCheckbox && ! $hideFields && $singleLine ) : ?>
				<div class="noptin-gdpr-checkbox-wrapper" style="margin-bottom: 10px;">
					<label><input type='checkbox' value='1' name='noptin_gdpr_checkbox' required="required"/><span><?php echo wp_kses_post( $gdprConsentText ); ?></span></label>
				</div>
			<?php endif; ?>

			<?php if ( ! $hideNote ) : ?>
				<div style="color:<?php echo esc_attr( $noteColor ); ?>;<?php echo esc_attr( $noteTypography['generated'] ); ?><?php echo esc_attr( $noteAdvanced['generated'] ); ?>" class="noptin-form-note"><?php echo wp_kses_post( do_shortcode( $note ) ); ?></div>
			<?php endif; ?>

			<div style="border:1px solid rgba(6, 147, 227, 0.8);display:none;padding:10px;margin-top:10px" class="noptin_feedback_success"></div>
			<div style="border:1px solid rgba(227, 6, 37, 0.8);display:none;padding:10px;margin-top:10px" class="noptin_feedback_error"></div>
		</div>

		<?php if ( 'popup' === $optinType || 'slide_in' === $optinType ) : ?>
			<span class="noptin-popup-close"
				title="close"><svg enable-background="new 0 0 24 24" id="Layer_1" version="1.0" viewBox="0 0 24 24"
					xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g>
						<path
							fill="<?php echo esc_attr( $descriptionColor ); ?>"
							d="M12,2C6.5,2,2,6.5,2,12c0,5.5,4.5,10,10,10s10-4.5,10-10C22,6.5,17.5,2,12,2z M16.9,15.5l-1.4,1.4L12,13.4l-3.5,3.5   l-1.4-1.4l3.5-3.5L7.1,8.5l1.4-1.4l3.5,3.5l3.5-3.5l1.4,1.4L13.4,12L16.9,15.5z" />
					</g>
				</svg>
			</span>
		<?php endif; ?>
	</form>

	<?php if ( $imageMain ) : ?>
		<div v-if="imageMain" class="noptin-form-main-image">
			<img src="<?php echo esc_url( $imageMain ); ?>" />
		</div>
	<?php endif; ?>

</div>
