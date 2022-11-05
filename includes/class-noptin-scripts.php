<?php

/**
 * Handles the loading of scripts.
 *
 * @since 1.9.0
 */
class Noptin_Scripts {

	/**
	 * An array of menu hooks and their scripts.
	 */
	protected static $admin_scripts = array();

	/**
	 * An array of frontend scripts.
	 */
	protected static $frontend_scripts = array();

	/**
	 * Inits the scripts.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ) );
	}

	/**
	 * Adds an admin script.
	 */
	public static function add_admin_script( $hook, $script ) {

		if ( ! isset( self::$admin_scripts[ $hook ] ) ) {
			self::$admin_scripts[ $hook ] = array();
		}

		self::$admin_scripts[ $hook ][] = $script;
	}

	/**
	 * Adds a frontend script.
	 */
	public static function add_frontend_script( $script ) {
		self::$frontend_scripts[] = $script;
	}

	/**
	 * Enqueues admin scripts.
	 */
	public static function admin_enqueue_scripts( $hook ) {

		// Maybe load default scripts.
		self::maybe_load_default_scripts( $hook );

		// Abort if we have no scripts to enqueue.
		if ( ! isset( self::$admin_scripts[ $hook ] ) ) {
			return;
		}

		foreach ( self::$admin_scripts[ $hook ] as $script ) {
			self::enqueue_script( $script );
		}
	}

	/**
	 * Loads default admin scripts.
	 */
	public static function maybe_load_default_scripts( $hook ) {
		global $current_screen;

		// Load our CSS styles on all pages.
		$assets_url = plugin_dir_url( __FILE__ ) . 'assets';
		$version    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : noptin()->version;
		wp_enqueue_style( 'noptin', $assets_url . '/css/admin.css', array(), $version );

		// Check if the hook suffix contains noptin.
		if ( false === strpos( $hook, 'noptin' ) && false === strpos( $hook, noptin()->white_label->admin_screen_id() ) && ( empty( $current_screen ) || false === strpos( $current_screen->id, 'noptin' ) ) ) {
			return;
		}

		// Remove AUI scripts as they break our pages.
		if ( class_exists( 'AyeCode_UI_Settings' ) && is_callable( 'AyeCode_UI_Settings::instance' ) ) {
			$aui = AyeCode_UI_Settings::instance();
			remove_action( 'admin_enqueue_scripts', array( $aui, 'enqueue_scripts' ), 1 );
			remove_action( 'admin_enqueue_scripts', array( $aui, 'enqueue_style' ), 1 );
		}

		// And EDD too.
		add_filter( 'edd_load_admin_scripts', '__return_false', 1000 );

		// Sweetalert https://sweetalert2.github.io/.
		wp_enqueue_script( 'promise-polyfill', $assets_url . '/vendor/sweetalert/promise-polyfill.min.js', array(), '8.1.3', true );
		wp_enqueue_script( 'sweetalert2', $assets_url . '/vendor/sweetalert/sweetalert2.all.min.js', array( 'promise-polyfill' ), '9.6.0', true );

		// Tooltips https://iamceege.github.io/tooltipster/.
		wp_enqueue_script( 'tooltipster', $assets_url . '/vendor/tooltipster/tooltipster.bundle.min.js', array( 'jquery' ), '4.2.7', true );
		wp_enqueue_style( 'tooltipster', $assets_url . '/vendor/tooltipster/tooltipster.bundle.min.css', array(), '4.2.7' );

		// Select 2 https://select2.org/.
		wp_enqueue_script( 'select2', $assets_url . '/vendor/select2/select2.full.min.js', array( 'jquery' ), '4.0.12', true );
		wp_enqueue_style( 'select2', $assets_url . '/vendor/select2/select2.min.css', array(), '4.0.12' );

		// Vue js.
		wp_register_script( 'vue', $assets_url . '/vendor/vue/vue.min.js', array(), '2.6.11', true );

		// Enque media for image uploads.
		wp_enqueue_media();

		// Enqueue main admin script.
		self::enqueue_script( 'admin' );
	}

	/**
	 * Enqueues frontend scripts.
	 */
	public static function wp_enqueue_scripts() {

		// Abort if we have no scripts to enqueue.
		if ( empty( self::$frontend_scripts ) ) {
			return;
		}

		foreach ( self::$frontend_scripts as $script ) {
			self::enqueue_script( $script );
		}
	}

	/**
	 * Enqueues a script.
	 */
	protected static function enqueue_script( $script ) {

		if ( is_string( $script ) ) {
			$data   = include plugin_dir_path( __FILE__ ) . "assets/js/dist/$script.asset.php";
			$script = array(
				'handle'       => 'noptin-' . $script,
				'dependencies' => $data['dependencies'],
				'version'      => $data['version'],
				'src'          => plugin_dir_url( __FILE__ ) . "assets/js/dist/$script.js",
			);
		}

		// Enqueue the script.
		wp_enqueue_script(
			$script['handle'],
			$script['src'],
			$script['dependencies'],
			$script['version'],
			true
		);

		// Localize the script.
		if ( ! empty( $script['localize'] ) ) {
			wp_localize_script( $script['handle'], $script['localize']['name'], $script['localize']['data'] );
		}

		self::maybe_localize_script( $script['handle'] );
	}

	/**
	 * Localizes a script.
	 *
	 * @param string $handle The script handle.
	 */
	public static function maybe_localize_script( $handle ) {

		if ( 'noptin-admin' === $handle ) {
			wp_localize_script(
				$handle,
				'noptin_params',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'api_url'        => get_home_url( null, 'wp-json/wp/v2/' ),
					'nonce'          => wp_create_nonce( 'noptin_admin_nonce' ),
					'icon'           => plugin_dir_url( __FILE__ ) . 'assets/images/checkmark.png',
					'admin_email'    => sanitize_email( wp_get_current_user()->user_email ),
					'close'          => __( 'Close', 'newsletter-optin-box' ),
					'cancel'         => __( 'Cancel', 'newsletter-optin-box' ),
					'donwload_forms' => add_query_arg(
						array(
							'action'      => 'noptin_download_forms',
							'admin_nonce' => wp_create_nonce( 'noptin_admin_nonce' ),
						),
						admin_url( 'admin-ajax.php' )
					),
				)
			);
		}

		if ( 'noptin-settings' === $handle ) {
			wp_localize_script( $handle, 'noptinSettings', array( 'app' => Noptin_Settings::get_state() ) );
		}

		if ( 'noptin-edit-automation-rule' === $handle ) {

			add_thickbox();

			// Fetch the rule.
			$rule = new Noptin_Automation_Rule( absint( $_GET['noptin_edit_automation_rule'] ) );

			if ( ! $rule->exists() ) {
				return wp_dequeue_script( $handle );
			}

			// Fetch the trigger.
			$trigger = noptin()->automation_rules->get_trigger( $rule->trigger_id );

			if ( ! $trigger ) {
				return wp_dequeue_script( $handle );
			}

			wp_localize_script(
				$handle,
				'noptinRules',
				array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'nonce'             => wp_create_nonce( 'noptin_automation_rules' ),
					'trigger_settings'  => (object) $rule->trigger_settings,
					'action_settings'   => (object) $rule->action_settings,
					'comparisons'       => noptin_get_conditional_logic_comparisons(),
					'smart_tags'        => $trigger->get_known_smart_tags(),
					'conditional_logic' => (object) $rule->conditional_logic,
					'rule_id'           => $rule->id,
					'error'             => __( 'Unable to save your changes.', 'newsletter-optin-box' ),
					'saved'             => __( 'Your automation rule has been saved.', 'newsletter-optin-box' ),
				)
			);
		}
	}

}