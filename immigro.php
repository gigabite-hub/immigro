<?php
/*
Plugin Name: Immigro
Description: Immigro with a shortcode to display custom post type content, single page template, and styles.
Version: 1.0
*/

// Enqueue style.css file
function custom_plugin_enqueue_styles() {
    // Get the path to the main plugin file
    $plugin_file = plugin_dir_url( __FILE__ );

    // Enqueue the style.css file
    wp_enqueue_style( 'custom-plugin-style', $plugin_file . 'style.css', array(), '1.0', 'all' );

    // Enqueue jQuery from WordPress core
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/2e71d1c020.js', array('jquery'), null, false);
    // Enqueue your custom jQuery file
    wp_enqueue_script( 'main.js', plugin_dir_url( __FILE__ ) . 'custom-script.js', array( 'jquery' ), '1.0', true );
	// wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery'), _S_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'custom_plugin_enqueue_styles' );


function get_list_name_of_all_cities() {
    ob_start();

    $cities_list = get_field('cities_list');
    
    if ($cities_list && is_array($cities_list)) : ?>
        <div class="cities-list">
            <ul>
                <?php foreach ($cities_list as $list): ?>
                    <li><i class="fa-solid fa-circle-arrow-right"></i> <?php echo esc_html($list->post_title); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif;

    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('get_cities_list', 'get_list_name_of_all_cities');
