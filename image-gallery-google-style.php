<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://imagegallerygooglestyle.com
 * @since             0.1
 * @package           image_gallery_google_style
 *
 * @wordpress-plugin
 * Plugin Name:       Image Gallery Google Style
 * Plugin URI:        https://imagegallerygooglestyle.com
 * Description:       Transform your regular WordPress galleries into elegant grids of thumbnails that open to display a larger image on click. [<a href=" https://imagegallerygooglestyle.com/upgrade">Upgrade for more options</a>]
 * Version:           0.27
 * Author:            Mike Hartley, bigflannel
 * Author URI:        http://bigflannel.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       image-gallery-google-style
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-image-gallery-google-style-activator.php
 */
function activate_image_gallery_google_style() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-image-gallery-google-style-activator.php';
	image_gallery_google_style_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-image-gallery-google-style-deactivator.php
 */
function deactivate_image_gallery_google_style() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-image-gallery-google-style-deactivator.php';
	image_gallery_google_style_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_image_gallery_google_style' );
register_deactivation_hook( __FILE__, 'deactivate_image_gallery_google_style' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-image-gallery-google-style.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1
 */
function run_image_gallery_google_style() {

	$plugin = new image_gallery_google_style();
	$plugin->run();

}
run_image_gallery_google_style();
