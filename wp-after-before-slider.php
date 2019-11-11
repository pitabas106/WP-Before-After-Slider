<?php
/**
 * Plugin Name: WP Before After Slider
 * Plugin URI:  https://github.io
 * Description: This plugin is designed to compare two different images.
 * Version:     1.0.0
 * Author:      Pitabas Behera
 * Author URI:  https://github.io/pitabas106
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-before-after-slider
 * Domain Path: /languages/
 *
 * @package wp-before-after-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// Define plugin paths & version
define( 'WP_BAS_FILE', __FILE__ );

define( 'WP_BAS_DIR', trailingslashit( plugin_dir_path( WP_BAS_FILE ) ) );

define( 'WP_BAS_URL', plugins_url( '/', WP_BAS_FILE ) );

define( 'WP_BAS_PATH', plugin_basename( WP_BAS_FILE ) );

define( 'WP_BAS_INC_DIR', trailingslashit ( WP_BAS_DIR . 'inc' ) );

define( 'WP_BAS_ASSETS_DIR', trailingslashit ( WP_BAS_DIR . 'assets' ) );

define( 'WP_BAS_VERSION', '1.0.0' );

define( 'WPBAS_NAME', 'wpbaslider' );

define( 'WPBAS_NAME_SPACE', '__wpbas__' );



if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', 'wp_bas_fail_php_version' );
} else {
	// Include the wp-before-after-slider class.
	require_once WP_BAS_INC_DIR . '/class-wp-before-after-slider.php';
}


/**
 * Admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wp_bas_fail_php_version() {

	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'WP Before After Slider requires PHP version %s+, plugin is currently NOT RUNNING.', 'pi-users-list' ), '5.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}


