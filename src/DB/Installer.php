<?php

namespace Hizzle\Noptin\DB;

/**
 * Contains the main DB installer class.
 *
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The main DB installer class.
 */
class Installer {

	/**
	 * Loads the class.
	 *
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 0 );
		add_action( 'init', array( __CLASS__, 'create_missing_tables' ), 0 );
	}

	/**
	 * Check the plugin version and run the updater if required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 */
	public static function check_version() {
		if ( self::needs_db_update() ) {
			self::install();
			do_action( 'noptin_updated' );
		}
	}

	/**
	 * Creates missing DB tables.
	 */
	public static function create_missing_tables() {
		if ( self::has_missing_tables() ) {
			self::verify_base_tables( true );
		}
	}

	/**
	 * Is a DB update needed?
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public static function needs_db_update() {
		return get_option( 'noptin_db_schema', null ) !== self::get_schema_hash();
	}

	/**
	 * Retrieves the database schema hash.
	 *
	 * @return string
	 */
	public static function get_schema_hash() {
		return md5( implode( ';', self::get_schema() ) );
	}

	/**
	 * Install.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'noptin_installing' ) ) {
			return;
		}

		// Prevent other instances from running simultaneously.
		set_transient( 'noptin_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		// Update DB tables.
		self::create_db_tables();

		// Verify DB tables.
		self::verify_base_tables();

		// Update the schema hash.
		update_option( 'noptin_db_schema', self::get_schema_hash() );

		// Allow other instances to run.
		delete_transient( 'noptin_installing' );

		// Fired after install or upgrade.
		do_action( 'noptin_installed' );
	}

	/**
	 * Get Table schema.
	 *
	 * @return array
	 */
	private static function get_schema() {
		return noptin()->db()->store->get_schema();
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 */
	private static function create_db_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( self::get_schema() );

	}

	/**
	 * Check if all the base tables are present.
	 *
	 * @param bool $execute Whether to execute get_schema queries as well.
	 *
	 * @return array List of querues.
	 */
	public static function verify_base_tables( $execute = false ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( $execute ) {
			self::create_db_tables();
		}

		$queries        = dbDelta( self::get_schema(), false );
		$missing_tables = array();
		foreach ( $queries as $table_name => $result ) {
			if ( "Created table $table_name" === $result ) {
				$missing_tables[] = $table_name;
			}
		}

		if ( 0 < count( $missing_tables ) ) {
			update_option( 'noptin_schema_missing_tables', $missing_tables );
		} else {
			delete_option( 'noptin_schema_missing_tables' );
		}

		return $missing_tables;
	}

	/**
	 * Checks if there are any missing tables.
	 *
	 * @return bool
	 */
	public static function has_missing_tables() {
		return (bool) get_option( 'noptin_schema_missing_tables', false );
	}
}
