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
		foreach( array( 'base', 'text', 'birthday', 'checkbox', 'date', 'dropdown', 'email', 'number', 'radio' ) as $file ) {
			require_once plugin_dir_path( __FILE__ ) . "field-types/class-$file.php";
		}

		do_action( 'noptin_load_custom_field_files' );

		foreach ( get_noptin_custom_field_types() as $type => $data ) {

			if ( ! empty( $data['class'] ) ) {
				$this->custom_field_types[ $type ] = new $data['class']( $type );
			}

		}

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
			)
		);

	}

}
