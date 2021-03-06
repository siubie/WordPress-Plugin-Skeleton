<?php
/*
Plugin Name: WordPress Plugin Skeleton
Plugin URI:  https://github.com/iandunn/WordPress-Plugin-Skeleton
Description: The skeleton for an object-oriented/MVC WordPress plugin
Version:     0.4a
Author:      Ian Dunn
Author URI:  http://iandunn.name
*/

/* 
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

define( 'WPPS_NAME',                 'WordPress Plugin Skeleton' );
define( 'WPPS_REQUIRED_PHP_VERSION', '5.3' ); // because of get_called_class()
define( 'WPPS_REQUIRED_WP_VERSION',  '3.1' ); // because of esc_textarea()

/**
 * Checks if the system requirements are met
 * @author Ian Dunn <ian@iandunn.name>
 * @return bool True if system requirements are met, false if not
 */
function wpps_requirements_met() {
	global $wp_version;
	//require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early

	if ( version_compare( PHP_VERSION, WPPS_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, WPPS_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}

	/*
	if ( ! is_plugin_active( 'plugin-directory/plugin-file.php' ) ) {
		return false;
	}
	*/

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * @author Ian Dunn <ian@iandunn.name>
 */
function wpps_requirements_error() {
	global $wp_version;
	$class = 'error';

	ob_start();
	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
	$message = ob_get_contents();
	ob_end_clean();

	require( dirname( __FILE__ ) . '/includes/IDAdminNotices/v-admin-notice.php' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( wpps_requirements_met() ) {
	require_once( dirname( __FILE__ ) . '/classes/wpps-module.php' );
	require_once( dirname( __FILE__ ) . '/classes/wordpress-plugin-skeleton.php' );

	if ( class_exists( 'WordPressPluginSkeleton' ) ) {
		$GLOBALS['wpps'] = WordPressPluginSkeleton::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpps'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpps'], 'deactivate' ) );
	}
} else {
	add_action( 'admin_notices', 'wpps_requirements_error' );
}

?>