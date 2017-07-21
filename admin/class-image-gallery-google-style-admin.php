<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://imagegallerygooglestyle.com
 * @since      0.1
 *
 * @package    Image_Gallery_Google_Style
 * @subpackage Image_Gallery_Google_Style/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Gallery_Google_Style
 * @subpackage Image_Gallery_Google_Style/admin
 * @author     @bigflannel
 */
class Image_Gallery_Google_Style_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $Image_Gallery_Google_Style    The ID of this plugin.
	 */
	private $Image_Gallery_Google_Style;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param      string    $Image_Gallery_Google_Style       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Image_Gallery_Google_Style, $version ) {

		$this->Image_Gallery_Google_Style = $Image_Gallery_Google_Style;
		$this->version = $version;
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Gallery_Google_Style_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Gallery_Google_Style_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Image_Gallery_Google_Style, plugin_dir_url( __FILE__ ) . 'css/image-gallery-google-style-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Gallery_Google_Style_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Gallery_Google_Style_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->Image_Gallery_Google_Style, plugin_dir_url( __FILE__ ) . 'js/image-gallery-google-style-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Function to add options to admin menu
	 *
	 * @since    0.14
	 */
	public function Image_Gallery_Google_Style_Private_Admin_Menu() {
		add_options_page( 'Image Gallery Google Style Options', 'Image Gallery Google Style', 'manage_options', 'image-gallery-google-style', array( $this, 'Image_Gallery_Google_Style_Private_Admin_Menu_Options' ) );
	}
	
	/**
	 * Function to add admin menu options screen
	 *
	 * @since    0.14
	 */
	public function Image_Gallery_Google_Style_Private_Admin_Menu_Options() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-display.php';
		
	}
	
	/**
	 * Function to add options to gallery admin screen
	 *
	 * @since    0.1
	 */
	public function Image_Gallery_Google_Style_Private_Add_Options() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/gallery-options.php';

	}
	
}
