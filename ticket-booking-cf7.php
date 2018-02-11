<?php

/**
 * Plugin Name: Ticket Booking CF7 add-on
 * Plugin URI: http://askaryabbas.com
 * Description: This plugin will generate shortcode [ticket_book_cf7] for booking system in your contact form.
 * Version: 1.0.0
 * Author: Askary Abbas
 * Author URI: http://askaryabbas.com
 * Text Domain: ticket-booking-cf
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
// Plugin version.
if ( !defined( 'TBC_VERSION' ) ) {
	define( 'TBC_VERSION', '1.0.0' );
}
// Plugin Folder Path.
if ( !defined( 'TBC_DIR' ) ) {
	define( 'TBC_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}
// Plugin Folder URL.
if ( !defined( 'TBC_URL' ) ) {
	define( 'TBC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
// Plugin Root File.
if ( !defined( 'TBC_FILE' ) ) {
	define( 'TBC_FILE', wp_normalize_path( __FILE__ ) );
}
// Plugin Text Domain
if ( !defined( 'TBC_TEXT_DOMAIN' ) ) {
	define( 'TBC_TEXT_DOMAIN', 'ticket-booking-cf' );
}

if ( !class_exists( 'TBC_Init' ) ) :

	/**
	 * Main TBC_Init Class.
	 *
	 * @since 1.0
	 */
	class TBC_Init {

		/**
		 * The one, true instance of this object.
		 *
		 * @static
		 * @access private
		 * @since 1.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 * @since 1.0.0
		 * @see TBC_Init::instance()
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'tbc_load_text_domain_init' ) );
			$this->includes();
		}

		/**
		 * Load Text Domain
		 */
		public function tbc_load_text_domain_init() {
			$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
			load_plugin_textdomain( TBC_TEXT_DOMAIN, false, $plugin_rel_path );
		}

		/*
		 * Function used for include required files
		 * @since 1.0.0
		 */

		public function includes() {
			$inc_files = array(
				'tbc-functions.php',
				'tbc-filters.php',
				'tbc-scripts.php',
				'tbc-ajax.php'
			);
			foreach ( $inc_files as $inc_file ) {
				include_once TBC_PLUGIN_DIR . 'inc/' . $inc_file;
			}
		}

	}

	/**
	 * Creating custom booking table
	 * @global Object $wpdb
	 * @since 1.0.0
	 */
	function tbc_create_booking_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'cf_ticket_booking';

		$charset_collate = $wpdb->get_charset_collate();
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

			$sql	 = "CREATE TABLE $table_name (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						name varchar(200) NOT NULL,
						value mediumint(9) DEFAULT 0 NOT NULL,
						PRIMARY KEY  (id)) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			//Inserting 100 fields
			$fdata	 = array_fill( 0, 100, 'event' );
			foreach ( $fdata as $key => $value ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name ( name, value ) VALUES ( %s, %d )", array( $value . '_' . $key, 0 ) ) );
			}
		}
	}

	/**
	 * Checking required plugin
	 * @since 1.0.0
	 */
	function tbc_checking_required_plugin() {
		$cf_active = in_array( 'contact-form-7/wp-contact-form-7.php', get_option( 'active_plugins' ) );
		if ( $cf_active !== true ) {
			add_action( 'admin_notices', 'tbc_plugin_admin_notice' );
		} else {
			register_activation_hook( __FILE__, 'tbc_create_booking_table' );
			TBC_Init::get_instance();
		}
	}

	/**
	 * Through admin notice if required plugin is not active.
	 * @since 1.0.0
	 */
	function tbc_plugin_admin_notice() {
		$tbc_plugin	 = __( 'Ticket Booking CF7 add-on', TBC_TEXT_DOMAIN );
		$cf_plugin	 = __( 'Contact Form 7', TBC_TEXT_DOMAIN );
		echo '<div class="error"><p>' . sprintf( __( '%1$s is ineffective now as it requires %2$s to be installed and active.', TBC_TEXT_DOMAIN ), '<strong>' . esc_html( $tbc_plugin ) . '</strong>', '<strong>' . esc_html( $cf_plugin ) . '</strong>' ) . '</p></div>';
		if ( isset( $_GET[ 'activate' ] ) )
			unset( $_GET[ 'activate' ] );
	}

	tbc_checking_required_plugin();
endif;