<?php defined( 'ABSPATH' ) || exit; ?>
<div class="misc-pub-section misc-pub-noptin-subscriber-id">
	<span id="subscriber-id">
		<span class="dashicons dashicons-admin-users" style="padding-right: 3px; color: #607d8b"></span>
		<?php esc_html_e( 'Subscriber ID:', 'newsletter-optin-box' ); ?>&nbsp;<b><?php echo esc_html( $subscriber->id ); ?></b>
	</span>
</div>

<?php if ( $subscriber->is_wp_user() ) : ?>
	<div class="misc-pub-section misc-pub-noptin-subscriber-wp-user">
		<span id="subscriber-wp-user">
			<span class="dashicons dashicons-yes" style="padding-right: 3px;color: #607d8b"></span>
			<a href="<?php echo esc_url( add_query_arg( 'user_id', $subscriber->get_wp_user(), self_admin_url( 'user-edit.php' ) ) ); ?>"><?php esc_html_e( 'View WordPress profile', 'newsletter-optin-box' ); ?></a>
		</span>
	</div>
<?php else : ?>

<div class="misc-pub-section misc-pub-noptin-subscriber-wp-user">
	<span id="subscriber-wp-user">
		<span class="dashicons dashicons-no" style="padding-right: 3px;color: #607d8b"></span>
		<?php esc_html_e( 'Not a registered user', 'newsletter-optin-box' ); ?>
	</span>
</div>
<?php endif; ?>


<div class="misc-pub-section misc-pub-noptin-subscriber-gdpr-consent">
	<span id="gdpr-consent">
		<span class="dashicons dashicons-email" style="padding-right: 3px; color: #607d8b;"></span>
			<?php esc_html_e( 'GDPR Consent:', 'newsletter-optin-box' ); ?>&nbsp;
			<?php
				$consent = $subscriber->get( 'GDPR_consent' );

				// Yes/No etc.
				if ( ! empty( $consent ) && ! is_numeric( $consent ) ) {
					echo '<b>' . esc_html( $consent ) . '</b>';
				} elseif ( ! empty( $consent ) ) {
					echo "<span style='color: #2e7d32;' class='dashicons dashicons-yes'></span>";
				} else {
					echo '<span style="color: #f44336;" class="dashicons dashicons-no"></span>';
				}
			?>
	</span>
</div>

<?php if ( $subscriber->ip_address ) : ?>
	<div class="misc-pub-section misc-pub-noptin-subscriber-ip-address">
		<span id="subscriber-ip-address">
			<span class="dashicons dashicons-admin-site" style="padding-right: 3px;color: #607d8b"></span>
			<?php esc_html_e( 'IP Address', 'newsletter-optin-box' ); ?>&nbsp;<b><?php echo esc_html( $subscriber->ip_address ); ?></b>
		</span>
	</div>
<?php endif; ?>

<?php if ( $subscriber->_subscriber_via ) : ?>
	<div class="misc-pub-section misc-pub-noptin-subscriber-subscribed-via">
		<span id="subscriber-subscribed-via">
			<span class="dashicons dashicons-art" style="padding-right: 3px;color: #607d8b"></span>
			<?php esc_html_e( 'Source', 'newsletter-optin-box' ); ?>:
			<b>
				<?php echo wp_kses_post( noptin_format_subscription_source( $subscriber->_subscriber_via ) ); ?>
			</b>
		</span>
	</div>
<?php endif; ?>

<?php if ( $subscriber->conversion_page ) : ?>
	<div class="misc-pub-section misc-pub-noptin-subscriber-conversion-page">
		<span id="subscriber-conversion-page">
			<span class="dashicons dashicons-admin-links" style="padding-right: 3px;color: #607d8b"></span>
			<?php esc_html_e( 'Conversion Page', 'newsletter-optin-box' ); ?>&nbsp;
			<b>
				<?php

					$url = $subscriber->conversion_page;
					if ( ! empty( $url ) ) {
						printf(
							'<a href="%1$s" style="display: block; font-weight: 400; word-break: break-word;" title="%1$s" target="_blank"><small>%1$s<small></a>',
							esc_url( $url )
						);
					} else {
						esc_html_e( 'Unknown', 'newsletter-optin-box' );
					}

				?>
			</b>
		</span>
	</div>
<?php endif; ?>

<?php do_action( 'noptin_subscribers_admin_save_changes', $subscriber ); ?>

<div id="major-publishing-actions" style="margin: 10px -12px -12px;">

	<div id="delete-action">
		<a class="submitdelete deletion" style="color: #a00;" href="<?php echo esc_attr( noptin_subscriber_delete_url( $subscriber->id ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this subscriber?', 'newsletter-optin-box' ); ?>');">
			<?php echo esc_html_e( 'Delete', 'newsletter-optin-box' ); ?>
		</a>
	</div>

	<div id="publishing-action">
		<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update', 'newsletter-optin-box' ); ?>">
	</div>
	<div class="clear"></div>
</div>
