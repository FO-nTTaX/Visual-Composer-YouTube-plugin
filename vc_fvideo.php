<?php
/*
Plugin Name: FO Video
Plugin URI: http://fo-nttax.de
Description: Extend Visual Composer with new video button
Version: 0.1
Author: Alex Winkler
Author URI: http://fo-nttax.de
License: GPLv2 or later
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCFVideoClass {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'fvideo', array( $this, 'renderFVideo' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("Videoplayer with thumbnail", 'vc_extend'),
            "description" => __("This buttons allows adding a youtube link with a thumbnail.", 'vc_extend'),
            "base" => "fvideo",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/FO.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('Content', 'js_composer'),
            //'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
            //'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __("Youtube ID", 'vc_extend'),
                    "param_name" => "fo_youtube_link",
                    "value" => __("dQw4w9WgXcQ", 'vc_extend'),
                    "description" => __("ID of YouTube video.", 'vc_extend')
                ),
	        array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __("Image Link", 'vc_extend'),
                    "param_name" => "fo_image_link",
                    "value" => __("http://www.teamliquid.net/staff/wo1fwood/TL/styleguide/logo_secondary.png", 'vc_extend'),
                    "description" => __("Description for foo param.", 'vc_extend')
                ),
	    )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderFVideo( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'fo_youtube_link' => 'dQw4w9WgXcQ',
        'fo_image_link' => 'http://www.teamliquid.net/staff/wo1fwood/TL/styleguide/logo_secondary.png'
      ), $atts ) );
      
      $iframe = '<iframe width=\\\'1060\\\' height=\\\'596\\\' src=\\\'http://www.youtube.com/embed/'.$fo_youtube_link.'?autoplay=1\\\' frameborder=\\\'0\\\' allowfullscreen></iframe>';
      $output = "<div class=\"fo_video\"><img style=\"cursor:pointer;\" onclick=\"this.parentElement.innerHTML = '".$iframe."';\" class=\"fo_thumbnail\" src=\"{$fo_image_link}\" /></div>";
      return $output;
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_extend_style', plugins_url('assets/vc_extend.css', __FILE__) );
      wp_enqueue_style( 'vc_extend_style' );

      // If you need any javascript files on front end, here is how you can load them.
      //wp_enqueue_script( 'vc_extend_js', plugins_url('assets/vc_extend.js', __FILE__), array('jquery') );
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new VCFVideoClass();