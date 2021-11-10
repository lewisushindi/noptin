<?php

/**
 * This class handles the display and management of custom fields.
 *
 * @since 1.5.5
 */
class Noptin_Custom_Fields {

	/**
	 * @param Noptin_Custom_Field_Type
	 */
	public $custom_field_types = array();

	/**
	 * Class Constructor.
	 *
	 * @since 1.5.5
	 */
	public function __construct() {

		// Load dependancies.
		foreach( array( 'base', 'text', 'textarea', 'birthday', 'checkbox', 'date', 'dropdown', 'email', 'number', 'radio' ) as $file ) {
			require_once plugin_dir_path( __FILE__ ) . "field-types/class-$file.php";
		}

		do_action( 'noptin_load_custom_field_files' );

		// Load custom field types.
		foreach ( get_noptin_custom_field_types() as $type => $data ) {

			if ( ! empty( $data['class'] ) ) {
				$this->custom_field_types[ $type ] = new $data['class']( $type );
			}

		}

		// Deprecated functionality.
		add_action( 'noptin_field_type_optin_markup', array( $this, 'output_preview' ) );
		add_action( 'noptin_field_type_frontend_optin_markup', array( $this, 'output_frontend' ) );
	}

	/**
	 * Get default fields
	 *
	 */
	public static function default_fields() {

		return array(
			array(
				'type'       => 'email',
				'merge_tag'  => 'email',
				'label'      => __( 'Email Address', 'newsletter-optin-box' ),
				'visible'    => true,
				'subs_table' => true,
				'predefined' => true,
			),
			array(
				'type'       => 'first_name',
				'merge_tag'  => 'first_name',
				'label'      => __( 'First Name', 'newsletter-optin-box' ),
				'visible'    => true,
				'subs_table' => true,
				'predefined' => true,
			),
			array(
				'type'       => 'last_name',
				'merge_tag'  => 'last_name',
				'label'      => __( 'Last Name', 'newsletter-optin-box' ),
				'visible'    => true,
				'subs_table' => true,
				'predefined' => true,
			),
			array(
				'type'       => 'birthday',
				'merge_tag'  => 'birthday',
				'label'      => __( 'Birthday', 'newsletter-optin-box' ),
				'visible'    => true,
				'subs_table' => false,
				'predefined' => true,
			),
		);

	}

	/**
	 * Displays the field preview in the old field editor.
	 *
	 * This is deprecated functionality.
	 * @deprecated
	 * @since 1.5.5
	 */
	public function output_preview() {

		foreach ( get_noptin_custom_fields() as $custom_field ) {

			if ( empty( $custom_field['type'] ) || empty( $this->custom_field_types[ $custom_field['type'] ] ) ) {
				continue;
			}

			$custom_field['name']  = $custom_field['merge_tag'];
			$custom_field['id']    = 'noptin_field_' . sanitize_html_class( $custom_field['merge_tag'] );
			$custom_field['value'] = '';
			$custom_field['vue']   = true;

			/**@var Noptin_Custom_Field_Type */
			$field      = $this->custom_field_types[ $custom_field['type'] ];
			$merge_tag  = esc_attr( $custom_field['merge_tag'] );
			$type       = esc_attr( $custom_field['type'] );


			echo "<div v-if=\"field.type.type=='$merge_tag'\" class='noptin-field-$type'>";
			$field->output( $custom_field, false );
			echo "</div>";

		}

	}

	/**
	 * Displays the field markup.
	 *
	 * This is deprecated functionality.
	 * @deprecated
	 * @since 1.5.5
	 */
	public function output_frontend( $field ) {

		foreach ( get_noptin_custom_fields() as $custom_field ) {

			if ( $field['type']['type'] !== $custom_field['merge_tag'] ) {
				continue;
			}

			if ( empty( $custom_field['type'] ) || empty( $this->custom_field_types[ $custom_field['type'] ] ) ) {
				continue;
			}

			$custom_field['name']     = $custom_field['merge_tag'];
			$custom_field['id']       = uniqid( sanitize_html_class( $custom_field['merge_tag'] ) );
			$custom_field['value']    = '';
			$custom_field['required'] = ! empty( $field['require'] ) && 'false' !== $field['require'];

			if ( ! empty( $field['type']['label'] ) ) {
				$custom_field['label']  = $field['type']['label'];
			}

			/**@var Noptin_Custom_Field_Type */
			$_field     = $this->custom_field_types[ $custom_field['type'] ];
			$type       = esc_attr( $custom_field['type'] );

			echo "<div class='noptin-field-$type'>";
			$_field->output( $custom_field, false );
			echo "</div>";

		}

	}

}