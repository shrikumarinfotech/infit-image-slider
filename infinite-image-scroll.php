<?php

/**
 * @link                debrajweb@gmail.com
 * @since               1.0.0
 * @package             Infinite_Image_Scroll
 * 
 * @wordpress-plugin
* Plugin Name:          Infinite Image Scroll
* Plugin URI:           debrajweb@gmail.com
* Description:          Scroll images continiously
* Version:              1.0.0
* Requires at least:    5.5.1
* Requires PHP:         7.4.10
* Author:               Debraj Rakshit
* Author URI:           debrajweb@gmail.com
* License:              GPLv2 or later
* License URI:          http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* Text Domain:          infinite-image-scroll
* Domain Path:          /languages
*/


// Infinite Image Scroll Starts here
// If this file is called directly abort
if( !defined('WPINC')){
    die;
}

/**
 * 1.0.0
 */
define('INFINITE_IMAGE_SCROLL_VERSION', '1.0.0');

/**
 * Activation hook
 */ 
function infinite_image_scroll_activate(){
    // Trigger our custom post type function
    infinite_image_scroll_post_type();
    // Clear the parmalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'infinite_image_scroll_activate');

/**
 * Deactivation hook
 */ 
function infinite_image_scroll_deactivate(){
    // Unregister the post type, so the rules are no longer in memory
    unregister_post_type('inf_scroll');
    // Clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'infinite_image_scroll_deactivate');


// register custom post type:
function infinite_image_scroll_post_type(){
    register_post_type('inf_scroll',
        array(
            'labels' => array(
                'name' => __('Inf Images', 'textdomain'),
                'singular_name' => __('Inf Image', 'textdomain'),
                'add_new' => __('Add Image', 'textdomain'),
                'add_new_item' => __('Add New Image', 'textdomain'),
                'edit_item' => __('Edit Image', 'textdomain'),
                'all_items' => __('All Images', 'textdomain'),
                'archives' => __('Image Archives', 'textdomain'),
                'attributes' => __('Image Attributes', 'textdomain'),
            ),
            'description' => 'Image for Infinite Scroll',
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'has_archive' => true,
            'supports' => array(
                'title',
                'thumbnail',
                'custom-fields',
                'post-formats'
            ),
            'rewrite' => array(
                'slug' => 'inf-image',
            ),
        )
    );
}
add_action('init', 'infinite_image_scroll_post_type');

// custom field to add image from media library
function infinite_image_scroll_add_image(){
    // TODO
}


/**
 * front-end functions
 */
// shortcode function
if( !shortcode_exists('infinite-image-scroll') ){
    add_shortcode('infinite-image-scroll', 'infinite_image_scroll_shortcode');
}

function infinite_image_scroll_shortcode( $atts=[], $inf_content = null ){
    // $inf_content // TODO: loop through each image and display
    $inf_content = '<div class="module-cont-img-carousel">
        <div class="module-carousel-wrapper">
            <div class="module-carousel-slides">
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-1.jpg" alt="slideshow slide 1"></div>
                </div>
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-2.jpg" alt="slideshow slide 2"></div>
                </div>
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-3.jpg" alt="slideshow slide 3"></div>
                </div>
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-4.jpg" alt="slideshow slide 4"></div>
                </div>
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-5.jpg" alt="slideshow slide 5"></div>
                </div>
                <div class="slide">
                    <div class="slide-image"><img class="slide-image-object" src="http://localhost/wp-infinite-image-scroll/wp-content/uploads/2020/10/slider-thubnail-6.jpg" alt="slideshow slide 6"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>';
    // return the $inf_content
    return $inf_content ;
}

// load css and js files
// Load CSS file
function infinite_image_scroll_enqueue_style(){
    wp_enqueue_style('infinite-image-scroll', plugins_url( '/public/css/infinite-image-scroll.css', __FILE__ ), false, true);
}
// Load JS files
function infinite_image_scroll_enqueue_script(){
    wp_register_script('jquery-3.5.1', plugins_url('/infinite-image-scroll/public/js/jquery-3.5.1.min.js'), false, true);
    wp_enqueue_script('jquery-3.5.1');
    wp_enqueue_script('infinite-image-scroll', plugins_url( '/public/js/infinite-image-scroll.js', __FILE__ ), false, true);
}

add_action('wp_enqueue_scripts', 'infinite_image_scroll_enqueue_style');
add_action('wp_enqueue_scripts', 'infinite_image_scroll_enqueue_script');



