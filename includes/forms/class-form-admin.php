<?php
/**
 * Forms API: Forms Admin.
 *
 * Contains the main admin class for Noptin forms
 *
 * @since   1.6.2
 * @package Noptin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The main admin class for Noptin forms.
 *
 * @since 1.6.2
 * @internal
 * @ignore
 */
class Noptin_Form_Admin {

	/**
	 * Add hooks
	 *
	 * @since  1.6.2
	 */
	public function add_hooks() {
		add_action( 'admin_init', array( $this, 'maybe_redirect_form_url' ) );
		add_action( 'noptin_after_register_menus', array( $this, 'add_editor_page' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Filters post row actions.
	 *
	 * @since  1.6.2
	 */
	public function maybe_redirect_form_url() {
		global $pagenow;

		if ( ! is_admin() || empty( $pagenow ) ) {
			return;
		}

		// Form edits.
		if ( 'post.php' === $pagenow && isset( $_GET['post'] ) && 'noptin-form' === get_post_type( (int) $_GET['post'] ) ) {

			// Only redirect if we're using the new forms editor.
			if ( ! is_legacy_noptin_form( (int) $_GET['post'] ) ) {
				wp_redirect( add_query_arg( 'form_id', (int) $_GET['post'], get_noptin_new_form_url() ) );
				exit;
			}

		}

		// Form creates.
		if ( is_using_new_noptin_forms() && 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && 'noptin-form' === $_GET['post_type'] ) {
			wp_redirect( get_noptin_new_form_url() );
			exit;
		}

	}

	/**
	 * Registers the editor page.
	 *
	 * @since  1.6.2
	 */
	public function add_editor_page() {

		add_submenu_page( 
			'noptin',
			'Forms Editor - Noptin',
			'Noptin Forms Editor',
			'manage_options',
			'noptin-form-editor',
			array( $this, 'display_form_editor_page' )
		);

	}

	/**
	 * Displays form editing page.
	 *
	 * @since  1.6.2
	 */
	public function display_form_editor_page() {

		if ( isset( $_GET['form_id'] ) ) {
			$form = new Noptin_Form( (int) $_GET['form_id'] );
		} else {
			$form = new Noptin_Form();
		}

		require_once plugin_dir_path( __FILE__ ) . 'views/editor.php';

		// Custom admin scripts.
		$version = filemtime( plugin_dir_path( Noptin::$file ) . 'includes/assets/js/dist/form-editor.js' );
		wp_enqueue_script( 'noptin-form-editor', plugin_dir_url( Noptin::$file ) . 'includes/assets/js/dist/form-editor.js', array( 'jquery', 'wp-color-picker' ), $version, true );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Registers the legacy form editing metabox.
	 *
	 * @since       1.6.2
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {

		if ( 'noptin-form' === $post_type ) {
			add_meta_box(
				'noptin_form_editor',
				__( 'Form Editor', 'newsletter-optin-box' ),
				array( $this, 'display_legacy_form_editor' ),
				$post_type,
				'normal',
				'high'
			);
		}

	}

	/**
	 * Displays form editing metabox.
	 *
	 * @param WP_Post $post
	 * @since  1.6.2
	 */
	public function display_legacy_form_editor( $post ) {
		require_once plugin_dir_path( __FILE__ ) . 'class-legacy-form-editor.php';
		$editor = new Noptin_Legacy_Form_Editor( $post->ID, true );
		$editor->output();
	}

}
