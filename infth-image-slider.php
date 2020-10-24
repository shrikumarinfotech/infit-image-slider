<?php

/**
 * Plugin Name
 * @link                https://github.com/debrajrakshit/infth-image-slider
 * @since               1.0.0
 * @package             INFth_Image_Slider
 * @license             GPLv2 or later
 * 
 * @wordpress-plugin
* Plugin Name:          INFth Image Slider
* Plugin URI:           https://github.com/debrajrakshit/infth-image-slider
* Description:          Scroll images horizontally
* Version:              1.0.0
* Requires at least:    5.5.1
* Requires PHP:         7.4.10
* Author:               Debraj Rakshit
* Author URI:           https://github.com/debrajrakshit/infth-image-slider
* License:              GPLv2 or later
* License URI:          http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* Text Domain:          infth-image-slider
* Domain Path:          /languages
*/

// INFth Image Slider Starts here
// If this file is called directly abort
if( !defined('WPINC')){
    die;
}

/**
 * 1.0.0
 */
define('INFTH_IMAGE_SLIDER_VERSION', '1.0.0');

/**
 * Activation hook
 */ 
function infth_image_slider_activate(){
    // Trigger our custom post type function
    infth_image_slider_post_type();
    // Clear the parmalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'infth_image_slider_activate');

/**
 * Deactivation hook
 */ 
function infth_image_slider_deactivate(){
    // Unregister the post type, so the rules are no longer in memory
    unregister_post_type('infthimageslider');
    // Clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'infth_image_slider_deactivate');

// register custom post type:
function infth_image_slider_post_type(){
    register_post_type('infthimageslider',
        array(
            'labels' => array(
                'name' => __('INFth Image Slider', 'infth-image-slider'),
                'singular_name' => __('INFth Image', 'infth-image-slider'),
                'add_new' => __('Add INFth Image', 'infth-image-slider'),
                'add_new_item' => __('Add New INFth Image', 'infth-image-slider'),
                'edit_item' => __('Edit INFth Image', 'infth-image-slider'),
                'all_items' => __('All INFth Images', 'infth-image-slider'),
                'archives' => __('INFth Image Archives', 'infth-image-slider'),
                'attributes' => __('INFth Image Attributes', 'infth-image-slider'),
            ),
            'description' => __('Images for INFth Image Slider', 'infth-image-slider'),
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
                'slug' => 'infthimageslider',
            ),
        )
    );
}
add_action('init', 'infth_image_slider_post_type');

// add image from media library
function infth_image_slider_add_image(){
    // add featured image support
    add_theme_support('post-thumbnails');
}
add_action('init', 'infth_image_slider_add_image');

/**
 * Custom Admin Options and Settings using WordPress Settings API, Options API
 * Reference URI: https://developer.wordpress.org/plugins/settings/custom-settings-page/
 */
function infth_image_slider_settings_init(){
    // Register a new setting for 'infth_image_slider' page
    register_setting('infth_image_slider', 'infth_image_slider_options');

    // Register a new section in the 'infth_image_slider' page
    add_settings_section(
        'infth_image_slider_section_developers',
        __( 'Update Options:', 'infth_image_slider'),
        'infth_image_slider_section_developers_callback',
        'infth_image_slider'
    );

    // Register a new field in the 'infth_image_slider_section_developers' section, inside th
    add_settings_field(
        'infth_image_slider_field_speed',
            __( 'Slider Speed:', 'infth_image_slider' ),
        'infth_image_slider_field_speed_cb',// cb stands for callback: https://developer.wordpress.org/reference/functions/add_settings_field/
        'infth_image_slider',
        'infth_image_slider_section_developers',
        array(
            'name'          => 'infth_image_slider_field_speed',
            'label_for'     => 'infth_image_slider_field_speed',
            'id'            => 'infth_image_slider_field_speed',
            'class'         => 'infth_image_slider_row',
            'infth_image_slider_custom_data'  =>  'custom',
        )
    );
}

/**
 * Register infth_image_slider_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'infth_image_slider_settings_init');

/**
 * Custom option and settings:
 *  - callback functions
 */

/**
 * Developers section callback function.infth-slide
 * 
 * @param array $args   The settings array, defining title, id, callback.
 */
function infth_image_slider_section_developers_callback( $args ){
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e('Insert only positive integer value.', 'infth_image_slider'); ?></p>
    <?php
}

/**
 * Slider Speed field callback function
 */
function infth_image_slider_field_speed_cb( $args ){
    // Get the value of the settings we've registered with register_settings()
    $options = get_option('infth_image_slider_options');
    $value = (!isset( $options[$args['name']])) ? null : $options[$args['name']];
    ?>

    <input
        type="number" 
        class="<?php esc_attr_e($args['id']); ?>" 
        name="infth_image_slider_options[<?php esc_attr_e($args['name']); ?>]" 
        value="<?php (esc_attr( $value ) < 1000 || esc_attr( $value ) > 50000 ) ? esc_attr_e(20000) : esc_attr_e( $value ); ?>"  
        placeholder="<?php esc_html_e( 20000, 'infth_image_slider' ); ?>"
    />

    <p class="description">
        <?php esc_html_e('Slider speed is in miliseconds. Like 5000 is equal to 5seconds. Default is 20000 or 20seconds. Minimum value should be 1000 or 1second.', 'infth_image_slider'); ?>
    </p>
    <?php
}

/**
 * Add the top level menu page
 */
function infth_image_slider_options_page(){
    add_menu_page(
        'INFthimageslider',
        'INFth Options',
        'manage_options',
        'infth_image_slider',
        'infth_image_slider_options_page_html'
    );
}

/**
 * Register infth_image_slider_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'infth_image_slider_options_page');

/**
 * Top Level menu callback function
 */
function infth_image_slider_options_page_html(){
    // check user capabilities
    if( !current_user_can('manage_options') ){
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if( isset( $_GET['settings-updated'] ) ){
        // add settings saved with the class of "updated"
        add_settings_error( 'infth_image_slider_messages', 'infth_image_slider_message', __( 'Settings Saved', 'infth_image_slider'), 'updated' );
    }

    // show error/update messages
    settings_errors('infth_image_slider_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "infth_image_slider"
            settings_fields('infth_image_slider');
            // output settings sections and their fields
            // (sections are registered for "infth_image_slider", each field is registered to a specific section)
            do_settings_sections('infth_image_slider');
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
// infthimageslider load all posts function
function infth_image_slider(){
    // query for post type infthimageslider and collect data as object
    $infthImagePosts = new WP_Query( array(
        'posts_per_page' => -1,
        'post_type' => 'infthimageslider',
        'order' => 'ASC'
    ) );
?>
    <div class="module-infth-image-slider">
        <div class="module-infth-image-wrapper">
            <div class="module-infth-image-slides">
                <?php if( $infthImagePosts->have_posts() ) : while ( $infthImagePosts->have_posts() ) : $infthImagePosts->the_post(); ?>
                    <div class="infth-slide">
                        <div class="infth-slide-image">
                            <img class="infth-slide-image-object" src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <p>No images found. Please upload some images.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="infth-image-slider-field-speed">
            <?php
                $options = get_option('infth_image_slider_options', false);
                if( $options ){
                    esc_attr_e( $options['infth_image_slider_field_speed'] );
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
function infth_image_slider_shortcode( $atts=[], $infth_image_content = null){
    // enclosing tags
    if( ! is_null($infth_image_content) ) {
        // secure output by executing the_content filter hook on $infth_image_content
        $infth_image_data = apply_filters('the_content', $infth_image_content);
        $infth_image_data .= infth_image_slider();
        // run shortcode parser recursively
        $infth_image_data .= do_shortcode($infth_image_content);
    }
    // return output
    return $infth_image_data;
}
function infth_image_slider_shortcodes_init(){
    if( !shortcode_exists('infth-image-slider') ){
        add_shortcode('infth-image-slider', 'infth_image_slider_shortcode');
    }    
}
add_action('init', 'infth_image_slider_shortcodes_init');

// Load plugin textdomain
function infth_image_slider_textdomain(){
    $test = load_plugin_textdomain('infth-image-slider', false, dirname(plugin_basename(__FILE__)) . '/languages' );
}
add_action('init', 'infth_image_slider_textdomain');

// Load CSS and JS files
// load CSS file
function infth_image_slider_enqueue_style(){
    wp_enqueue_style('infth-image-slider', plugins_url( '/public/css/infth-image-slider.css', __FILE__ ), false, true);
}
// load JS files
function infth_image_slider_enqueue_script(){
    wp_register_script('jquery-3.5.1', plugins_url('/infth-image-slider/public/js/jquery-3.5.1.min.js'), false, true);
    wp_enqueue_script('jquery-3.5.1');
    wp_enqueue_script('infth-image-slider', plugins_url( '/public/js/infth-image-slider.js', __FILE__ ), false, true);
}

add_action('wp_enqueue_scripts', 'infth_image_slider_enqueue_style');
add_action('wp_enqueue_scripts', 'infth_image_slider_enqueue_script');