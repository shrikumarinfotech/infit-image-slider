<?php

/**
 * Plugin Name
 * @link                https://github.com/shrikumarinfotech/infit-image-slider
 * @since               1.0.0
 * @package             INFit_Image_Slider
 * @license             GPLv2 or later
 * 
 * @wordpress-plugin
* Plugin Name:          INFit Image Slider
* Plugin URI:           https://github.com/shrikumarinfotech/infit-image-slider
* Description:          Scroll images horizontally in continious motion
* Version:              1.0.0
* Requires at least:    5.5.1
* Requires PHP:         7.4.10
* Author:               Shrikumar Infotech
* License:              GPLv2 or later
* License URI:          http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* Text Domain:          infit-image-slider
* Domain Path:          /languages
*/

// INFit Image Slider Starts here
// If this file is called directly abort
if( !defined('WPINC')){
    die;
}

/**
 * 1.0.0
 */
define('INFIT_IMAGE_SLIDER_VERSION', '1.0.0');

/**
 * Activation hook
 */ 
function infit_image_slider_activate(){
    // Trigger our custom post type function
    infit_image_slider_post_type();
    // Clear the parmalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'infit_image_slider_activate');

/**
 * Deactivation hook
 */ 
function infit_image_slider_deactivate(){
    // Unregister the post type, so the rules are no longer in memory
    unregister_post_type('infitimageslider');
    // Clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'infit_image_slider_deactivate');
/**
 * Register custom post type
 */
function infit_image_slider_post_type(){
    register_post_type('infitimageslider',
        array(
            'labels' => array(
                'name' => __('INFit Image Slider', 'infit-image-slider'),
                'singular_name' => __('INFit Image', 'infit-image-slider'),
                'add_new' => __('Add INFit Image', 'infit-image-slider'),
                'add_new_item' => __('Add New INFit Image', 'infit-image-slider'),
                'edit_item' => __('Edit INFit Image', 'infit-image-slider'),
                'all_items' => __('All INFit Images', 'infit-image-slider'),
                'archives' => __('INFit Image Archives', 'infit-image-slider'),
                'attributes' => __('INFit Image Attributes', 'infit-image-slider'),
            ),
            'description' => __('Images for INFit Image Slider', 'infit-image-slider'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'has_archive' => true,
            'supports' => array(
                'title',
                'thumbnail',
                'post-formats'
            ),
            'rewrite' => array(
                'slug' => 'infitimageslider',
            ),
        )
    );
}
add_action('init', 'infit_image_slider_post_type');

/**
 * Add image from media library
 */
function infit_image_slider_add_image(){
    // add featured image support
    add_theme_support('post-thumbnails');
}
add_action('init', 'infit_image_slider_add_image');

/**
 * Custom Admin Options and Settings using WordPress Settings API, Options API
 * Reference URI: https://developer.wordpress.org/plugins/settings/custom-settings-page/
 */
function infit_image_slider_settings_init(){
    // Register a new setting for 'infit_image_slider' page
    register_setting('infit_image_slider', 'infit_image_slider_options');

    // Register a new section in the 'infit_image_slider' page
    add_settings_section(
        'infit_image_slider_section_developers',
        __( 'Update Options:', 'infit_image_slider'),
        'infit_image_slider_section_developers_callback',
        'infit_image_slider'
    );

    // Register a new field in the 'infit_image_slider_section_developers' section, inside th
    add_settings_field(
        'infit_image_slider_field_speed',
            __( 'Slider Speed:', 'infit_image_slider' ),
        'infit_image_slider_field_speed_cb',// cb stands for callback: https://developer.wordpress.org/reference/functions/add_settings_field/
        'infit_image_slider',
        'infit_image_slider_section_developers',
        array(
            'name'          => 'infit_image_slider_field_speed',
            'label_for'     => 'infit_image_slider_field_speed',
            'id'            => 'infit_image_slider_field_speed',
            'class'         => 'infit_image_slider_row',
            'infit_image_slider_custom_data'  =>  'custom',
        )
    );
}

/**
 * Register infit_image_slider_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'infit_image_slider_settings_init');

/**
 * Custom option and settings:
 *  - admin options panel
 *  - callback functions
 */

/**
 * Developers section callback function.infit-slide
 * 
 * @param array $args   The settings array, defining title, id, callback.
 */
function infit_image_slider_section_developers_callback( $args ){
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e('Insert only positive integer value.', 'infit_image_slider'); ?></p>
    <?php
}

/**
 * Slider Speed field callback function
 */
function infit_image_slider_field_speed_cb( $args ){
    // Get the value of the settings we've registered with register_settings()
    $options = get_option('infit_image_slider_options');
    $value = (!isset( $options[$args['name']])) ? null : $options[$args['name']];
    ?>
    <input
        type="number" 
        class="<?php esc_attr_e($args['id']); ?>" 
        name="infit_image_slider_options[<?php esc_attr_e($args['name']); ?>]" 
        value="<?php (esc_attr( $value ) < 1000 || esc_attr( $value ) > 50000 ) ? esc_attr_e(20000) : esc_attr_e( $value ); ?>"  
        placeholder="<?php esc_html_e( 20000, 'infit_image_slider' ); ?>"
    />

    <p class="description">
        <?php esc_html_e('Slider speed is in miliseconds. Like 5000 is equal to 5seconds. Default is 20000 or 20seconds. Minimum value should be 1000 or 1second.', 'infit_image_slider'); ?>
    </p>
    <?php
}

/**
 * Add the top level menu page
 */
function infit_image_slider_options_page(){
    add_menu_page(
        'INFitimageslider',
        'INFit Options',
        'manage_options',
        'infit_image_slider',
        'infit_image_slider_options_page_html'
    );
}

/**
 * Register infit_image_slider_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'infit_image_slider_options_page');

/**
 * Top Level menu callback function
 */
function infit_image_slider_options_page_html(){
    // check user capabilities
    if( !current_user_can('manage_options') ){
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if( isset( $_GET['settings-updated'] ) ){
        // add settings saved with the class of "updated"
        add_settings_error( 'infit_image_slider_messages', 'infit_image_slider_message', __( 'Settings Saved', 'infit_image_slider'), 'updated' );
    }

    // show error/update messages
    settings_errors('infit_image_slider_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "infit_image_slider"
            settings_fields('infit_image_slider');
            // output settings sections and their fields
            // (sections are registered for "infit_image_slider", each field is registered to a specific section)
            do_settings_sections('infit_image_slider');
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
/**
 * Render Slider
 */
// infitimageslider load all posts function
function infit_image_slider(){
    // query for post type infitimageslider and collect data as object
    $infitImagePosts = new WP_Query( array(
        'posts_per_page' => -1,
        'post_type' => 'infitimageslider',
        'order' => 'ASC'
    ) );
?>
    <div class="module-infit-image-slider">
        <div class="module-infit-image-wrapper">
            <div class="module-infit-image-slides">
                <?php if( $infitImagePosts->have_posts() ) : while ( $infitImagePosts->have_posts() ) : $infitImagePosts->the_post(); ?>
                    <div class="infit-slide">
                        <div class="infit-slide-image">
                            <img class="infit-slide-image-object" src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <p>No images found. Please upload some images.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="infit-image-slider-field-speed">
            <?php
                $options = get_option('infit_image_slider_options', false);
                if( $options ){
                    esc_attr_e( $options['infit_image_slider_field_speed'] );
                }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
}
/**
 * Front-end functions
 */
// Shortcode function
function infit_image_slider_shortcode( $atts=[], $infit_image_content = null){
    // enclosing tags
    if( ! is_null($infit_image_content) ) {
        // secure output by executing the_content filter hook on $infit_image_content
        $infit_image_data = apply_filters('the_content', $infit_image_content);
        $infit_image_data .= infit_image_slider();
        // run shortcode parser recursively
        $infit_image_data .= do_shortcode($infit_image_content);
    }
    // return output
    return $infit_image_data;
}
function infit_image_slider_shortcodes_init(){
    if( !shortcode_exists('infit-image-slider') ){
        add_shortcode('infit-image-slider', 'infit_image_slider_shortcode');
    }    
}
add_action('init', 'infit_image_slider_shortcodes_init');

/**
 * Load plugin textdomain
 */
function infit_image_slider_textdomain(){
    $test = load_plugin_textdomain('infit-image-slider', false, dirname(plugin_basename(__FILE__)) . '/languages' );
}
add_action('init', 'infit_image_slider_textdomain');

/**
 * Load CSS and JS files
 */
// load CSS file
function infit_image_slider_enqueue_style(){
    wp_enqueue_style('infit-image-slider', plugins_url( '/public/css/infit-image-slider.css', __FILE__ ), false, true);
}
// load JS files
function infit_image_slider_enqueue_script(){
    wp_enqueue_script('infit-image-slider', plugins_url( '/public/js/infit-image-slider.js', __FILE__ ), array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'infit_image_slider_enqueue_style');
add_action('wp_enqueue_scripts', 'infit_image_slider_enqueue_script');