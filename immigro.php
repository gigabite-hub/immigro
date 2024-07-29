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

function get_cities_with_hover_cards() {
    // Start output buffering
    ob_start();

    // Get the list of cities
    $cities_list = get_field('cities_list');

    if ($cities_list && is_array($cities_list)) : ?>
        <div class="cities-cards-container">
            <?php foreach ($cities_list as $city) : ?>
                <div class="city-card">
                    <div class="city-card-image">
                        <?php
                        // Assuming the city object has fields for image URL and icon URL
                        $image_url = get_the_post_thumbnail_url($city->ID);
                        $card_icon = get_field( 'card_icon', $city->ID );
                        ?>
                        <img class="citi-image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($city->post_title); ?>">
                        <h3><?php echo esc_html($city->post_title); ?></h3>
                        <?php if ($card_icon) : ?>
                            <div class="city-card-icon">
                                <img src="<?php echo esc_url( $card_icon['url'] ); ?>" alt="<?php echo esc_attr( $card_icon['alt'] ); ?>" />
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="city-card-content">
                        <h3><?php echo esc_html($city->post_title); ?></h3>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($city->ID), 8, '')); ?></p>
                        <a href="<?php echo esc_url(get_permalink($city->ID)); ?>" class="read-more-link">
                            Read More <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif;

    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('cities_with_hover_cards', 'get_cities_with_hover_cards');


function display_related_blogs() {
    // Start output buffering
    ob_start();

    // Get the list of related blogs
    $related_blogs = get_field('related_blogs');

    if ($related_blogs && is_array($related_blogs)) : ?>
        <div class="related-blogs-container">
            <?php foreach ($related_blogs as $post): ?>
                <?php setup_postdata($post); ?>
                <div class="related-blog-card">
                    <div class="card-image">
                        <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
                        <a href="<?php echo get_permalink($post->ID); ?>" class="overlay">
                        <i class="fa-solid fa-arrow-right"></i>
                        </a>    
                    </div>
                    <a href="<?php echo get_permalink($post->ID); ?>" class="overlay-visible">
                    <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="<?php echo get_permalink($post->ID); ?>" class="card-content">
                        <h3><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($post->ID), 15)); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    <?php endif;

    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('related_blogs', 'display_related_blogs');
