<?php

/**
 * Provide options for gallery admin screen
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://imagegallerygooglestyle.com
 * @since      0.1
 *
 * @package    image_gallery_google_style
 * @subpackage image_gallery_google_style/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php

// define your backbone template;
// the "tmpl-" prefix is required,
// and your input field should have a data-setting attribute
// matching the shortcode name

?>

<script type="text/html" id="tmpl-iggs-imagegallerygooglestyle">
    <label class="setting">
    	<hr />
		<span>Image Gallery Google Style</span>
		<input type="checkbox" data-setting="imagegallerygooglestyle">
		<div><span class="iggs-gallery-setting-text"><?php _e('<a href=" https://imagegallerygooglestyle.com/upgrade">Upgrade</a> for more options.'); ?></span></div>
		<hr />
	</label>        
</script>
 
<script>
    jQuery(document).ready(function () {           
        // custom attributes
        _.extend(wp.media.gallery.defaults, {
            imagegallerygooglestyle: 'false'
        });        
        // merge default gallery settings template with custom
		wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
			template: function(view){
				return wp.media.template('gallery-settings')(view)
		    	+ wp.media.template('iggs-imagegallerygooglestyle')(view);
			}
		});        	        
 	});
</script>