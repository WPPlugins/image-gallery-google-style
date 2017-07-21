<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://imagegallerygooglestyle.com
 * @since      0.1
 *
 * @package    image_gallery_google_style
 * @subpackage image_gallery_google_style/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1
 * @package    image_gallery_google_style
 * @subpackage image_gallery_google_style/includes
 * @author     @bigflannel
 */
class image_gallery_google_style_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'image-gallery-google-style',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
