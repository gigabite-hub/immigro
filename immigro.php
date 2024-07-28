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

function display_gallery_images() {
    // Start output buffering
    ob_start();

    // Get the gallery field
    $images = get_field('gallery');

    // Check if there are images
    if ($images && is_array($images)): ?>
        <div class="image-gallery">
            <?php foreach ($images as $image): ?>
                <figure>
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php if (!empty($image['caption'])): ?>
                        <figcaption><?php echo esc_html($image['caption']); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php endif;

    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('display_gallery', 'display_gallery_images');


function display_accordions_faqs() {
    // Start output buffering
    ob_start();

    $accordions_details = get_field('accordions_details'); ?>

    <div class="accoedion-details">
        <p><?php echo esc_html($accordions_details); ?></p>
    </div><?php

    // Check if the repeater field has rows of data
    if (have_rows('accordions_faqs')): ?>
        <div class="faqs-accordion">
            <?php while (have_rows('accordions_faqs')): the_row(); 
                $heading = get_sub_field('heading_faqs');
                $description = get_sub_field('description_faqs'); ?>
                
                <div class="faq-item">
                    <div class="faq-heading"><?php echo esc_html($heading); ?></div>
                    <div class="faq-description" style="display: none;"><?php echo wp_kses_post($description); ?></div>
                </div>
                
            <?php endwhile; ?>
        </div>
    <?php endif;

    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('display_faqs', 'display_accordions_faqs');

