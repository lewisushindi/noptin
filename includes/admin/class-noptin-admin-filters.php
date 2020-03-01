<?php
/**
 * Registers admin filters and actions
 *
 * @since             1.2.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Main Class
 *
 * @since       1.2.4
 */
class Noptin_Admin_Filters {

	/**
	 * Class constructor.
	 * @since       1.2.4
	 */
	public function __construct() {
		
		add_filter( 'noptin_admin_tools_page_title', array( $this, 'filter_tools_page_titles' ) );
		do_action( 'delete_noptin_subscriber', array( $this, 'delete_subscriber_user_link' ) );

		// Show subscriber connection on user's list table.
        add_filter( 'manage_users_columns', array( $this, 'modify_users_table' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'modify_users_table_row' ), 10, 3 );
		
		// Single subscribers.
		add_filter( "noptin_subscriber_wp_user_id_label", array( $this, 'wp_user_id_label' ) );
		add_filter( "noptin_format_subscriber_wp_user_id", array( $this, 'format_user_id' ), 10, 2 );

	}

	/**
	 * Filters tools page titles.
	 * @since       1.2.4
	 */
	public function filter_tools_page_titles( $title ) {
		
		$titles = array(
			'debug_log'	   => __( 'Debug Log', 'newsletter-optin-box' ),
			'system_info'  => __( 'System Information', 'newsletter-optin-box' ),
		);

		if ( isset( $_GET['tool'] ) && isset( $titles[ $_GET['tool'] ] ) ) {
			return $titles[ $_GET['tool'] ];
		}

		return $title;

	}

	/**
	 * Deletes a subscriber > user connection.
	 * @since       1.2.4
	 * @param int $subscriber_id The id of the subscriber being deleted
	 */
	public function delete_subscriber_user_link( $subscriber_id ) {
		$user_id = get_noptin_subscriber_meta ( $subscriber_id, 'wp_user_id', true );

		if ( ! empty( $user_id ) ) {
			delete_noptin_subscriber_meta( $subscriber_id, 'wp_user_id' );
			delete_user_meta ( $user_id, 'noptin_subscriber_id' );
		}

	}

	/**
	 * Adds a user's subscription status column
	 * @since       1.2.4
	 * @param array $columns User columns
	 */
	public function modify_users_table( $columns ) {
        $columns['noptin_subscriber'] = __( 'Email Subscriber', 'newsletter-optin-box' );
        return $columns;
	}
	
	/**
	 * Displays a user's subscription status
	 * @since       1.2.4
	 * @param mixed $val The current column value.
	 * @param string $column_name The current column name.
	 * @param int $user_id The current user id.
	 */
	public function modify_users_table_row( $val, $column_name, $user_id ) {

        switch ( $column_name ) {
			case 'noptin_subscriber' :
				$subscriber_id = get_user_meta ( $user_id, 'noptin_subscriber_id', true );

				if ( $subscriber_id ) {
					$subscriber_id = (int) $subscriber_id;
					$view_url      = esc_url( admin_url( "admin.php?page=noptin-subscribers&subscriber=$subscriber_id" ) );
					$text          = __( 'View', 'newsletter-optin-box' );
					return "<span style='color: #2e7d32;' class='dashicons dashicons-yes'></span><a href='$view_url' class='description'>$text</a>";
				}
                return '<span style="color: #f44336;" class="dashicons dashicons-no"></span>';
            default:
        }
        return $val;

	}
	
	/**
	 * Returns the wp_user_id key label.
	 */
	public function wp_user_id_label(){
		return __( 'Registered user', 'newsletter-optin-box' );
	}

	/**
	 * Formats the user id.
	 * 
	 * @param int $user_id The subscriber's user id.
	 */
	public function format_user_id( $user_id, $data ){
		$user_id = ( int ) $user_id[0];
		$user = get_user_by( 'id', $user_id );

		if( $user ) {
			$login = esc_html( $user->user_login );
			return "<span style='color: #2e7d32;' class='dashicons dashicons-yes'></span>($login)";
		} 

		delete_noptin_subscriber_meta( $data->id, 'wp_user_id' );
		return '<span style="color: #f44336;" class="dashicons dashicons-no"></span>';
	}

}
