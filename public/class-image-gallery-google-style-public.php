<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://imagegallerygooglestyle.com
 * @since      0.1
 *
 * @package    Image_Gallery_Google_Style
 * @subpackage Image_Gallery_Google_Style/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Gallery_Google_Style
 * @subpackage Image_Gallery_Google_Style/public
 * @author     @bigflannel
 */
class Image_Gallery_Google_Style_Public {

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
	 * @param      string    $Image_Gallery_Google_Style       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Image_Gallery_Google_Style, $version ) {

		$this->Image_Gallery_Google_Style = $Image_Gallery_Google_Style;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->Image_Gallery_Google_Style, plugin_dir_url( __FILE__ ) . 'css/image-gallery-google-style-public.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( 'icons', plugin_dir_url( __FILE__ ) . 'css/image-gallery-google-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->Image_Gallery_Google_Style, plugin_dir_url( __FILE__ ) . 'js/image-gallery-google-style-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add shortcodes
	 *
	 * @since    0.1
	 */
	public function register_shortcodes() {

		add_shortcode( 'gallery', array( 'Image_Gallery_Google_Style_Public', 'Image_Gallery_Google_Style_Public_Shortcode' ) );	

	}
	
	/**
	 * Render Image Gallery Google Style
	 *
	 * @since    0.1
	 */
	public static function Image_Gallery_Google_Style_Public_Shortcode( $attr ) {
		
		if ( empty( $attr['imagegallerygooglestyle'] ) ) {
			$attr['imagegallerygooglestyle'] = false;
		}

		if ( $attr['imagegallerygooglestyle'] == true ) {			
			if ( !empty( $attr['ids'] ) ) {
				$imageIDs = explode( ',', $attr['ids'] );
				if ( !empty( $attr['orderby'] ) ) :
					if ( $attr['orderby'] == 'rand') :
						shuffle( $imageIDs );
					endif;
				endif;
				$output = '<div class="iggs-thumbgrid"><ul class="iggs-grid iggs-group"><li>';
				foreach ( $imageIDs as $imageID ) {
					$imageThumbSRC = wp_get_attachment_image_src( $imageID,'thumbnail' );
					$imageMediumSRC = wp_get_attachment_image_src( $imageID,'medium' );
					$imageLargeSRC = wp_get_attachment_image_src( $imageID,'large' );
					$imageData = get_post( $imageID );
					$imageTitle = $imageData->post_title;
					$imageCaption = $imageData->post_excerpt;
					$imageLink = get_attachment_link( $imageID );
					$output = $output . '</li><li><a href="' . $imageLink . '"><img class="iggs-grid-thumb" title="' . htmlspecialchars($imageTitle) . '" alt="' . htmlspecialchars($imageTitle) . '" src="' . $imageThumbSRC[0] . '" srcset="' . $imageThumbSRC[0] . ' ' . $imageThumbSRC[1] . 'w, ' . $imageMediumSRC[0] . ' ' . $imageMediumSRC[1] . 'w, ' . $imageLargeSRC[0] . ' ' . $imageLargeSRC[1] . 'w" sizes="50vw" data-iggswidth="' . $imageThumbSRC[1] . '" data-iggsheight="' . $imageThumbSRC[2] . '" data-iggssmallsrc="' . $imageThumbSRC[0] . '" data-iggssmallheight="' . $imageThumbSRC[2] . '" data-iggsmediumsrc="' . $imageMediumSRC[0] . '" data-iggsmediumheight="' . $imageMediumSRC[2] . '" data-iggslargesrc="' . $imageLargeSRC[0] . '" data-iggslargeheight="' . $imageLargeSRC[2] . '" data-iggstitle="' . htmlspecialchars($imageTitle) . '" data-iggscaption="' . htmlspecialchars($imageCaption) . '" data-iggslink="' . $imageLink . '" /></a>';	
				}
				$output = $output . '</li></ul></div>';
			} else {
				$images = get_attached_media( 'image' );
				$imageIDs = array();
				if( !empty( $images ) ) {
					foreach( $images as $image ) {
						$imageIDs[] = $image->ID;
					}
					if ( $attr['orderby'] == 'rand') :
						shuffle( $imageIDs );
					endif;
					$output = '<div class="iggs-thumbgrid"><ul class="iggs-grid iggs-group"><li>';
					foreach ( $imageIDs as $imageID ) {
						$imageThumbSRC = wp_get_attachment_image_src( $imageID,'thumbnail' );
						$imageMediumSRC = wp_get_attachment_image_src( $imageID,'medium' );
						$imageLargeSRC = wp_get_attachment_image_src( $imageID,'large' );
						$imageData = get_post( $imageID );
						$imageTitle = $imageData->post_title;
						$imageCaption = $imageData->post_excerpt;
						$imageLink = get_attachment_link( $imageID );
						$output = $output . '</li><li><a href="' . $imageLink . '"><img class="iggs-grid-thumb" title="' . htmlspecialchars($imageTitle) . '" alt="' . htmlspecialchars($imageTitle) . '" src="' . $imageThumbSRC[0] . '" srcset="' . $imageThumbSRC[0] . ' ' . $imageThumbSRC[1] . 'w, ' . $imageMediumSRC[0] . ' ' . $imageMediumSRC[1] . 'w, ' . $imageLargeSRC[0] . ' ' . $imageLargeSRC[1] . 'w" sizes="50vw" data-iggswidth="' . $imageThumbSRC[1] . '" data-iggsheight="' . $imageThumbSRC[2] . '" data-iggssmallsrc="' . $imageThumbSRC[0] . '" data-iggssmallheight="' . $imageThumbSRC[2] . '" data-iggsmediumsrc="' . $imageMediumSRC[0] . '" data-iggsmediumheight="' . $imageMediumSRC[2] . '" data-iggslargesrc="' . $imageLargeSRC[0] . '" data-iggslargeheight="' . $imageLargeSRC[2] . '" data-iggstitle="' . htmlspecialchars($imageTitle) . '" data-iggscaption="' . htmlspecialchars($imageCaption) . '" data-iggslink="' . $imageLink . '" /></a>';
					}
					$output = $output . '</li></ul></div>';
				} else {
					$output = 'no images attached to gallery';
				}
			}
		} else {
			$output = gallery_shortcode( $attr );		
		}
		return $output;	
	}

}
