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

    // Check if the current post type is 'city' and it's a singular page
    if (is_singular('city')) {
        $items_list = get_field('institutes');
    } else {
        $items_list = get_field('cities_list');
    }

    if ($items_list && is_array($items_list)) : ?>
        <div class="cities-list institutes-list">
            <ul>
                <?php foreach ($items_list as $item): ?>
                    <li><i class="fa-solid fa-circle-arrow-right"></i> <?php echo esc_html($item->post_title); ?></li>
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

    // Check if the current post type is 'escuelas'
    if (is_singular('escuelas')) {
        // Get the list of programs for 'escuelas'
        $programs_escuelas = get_field('programs_escuelas');
        $list_to_display = $programs_escuelas;
        $unique_class = 'escuelas-programs-container';
        $excerpt_length = 15;
    } else {
        // Get the list of cities
        $cities_list = get_field('cities_list');
        $list_to_display = $cities_list;
        $unique_class = 'cities-list-container';
        $excerpt_length = 8;
    }

    // Ensure that the list is an array and not empty
    if ($list_to_display && is_array($list_to_display)) : ?>
        <div class="cities-cards-container <?php echo esc_attr($unique_class); ?>">
            <?php foreach ($list_to_display as $item) : ?>
                <div class="city-card">
                    <div class="city-card-image">
                        <?php
                        // Assuming the item object has fields for image URL and icon URL
                        $image_url = get_the_post_thumbnail_url($item->ID);
                        $card_icon = get_field('card_icon', $item->ID);
                        ?>
                        <img class="citi-image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>">
                        <h3><?php echo esc_html($item->post_title); ?></h3>

                        <?php if (is_singular('escuelas')) : ?>
                            <div class="city-card-icon">
                                <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'img/arrow-right-solid.svg'); ?>" alt="">
                            </div>
                        <?php else : ?>
                            <?php 
                            // Display the card icon if it exists and is not 'escuelas'
                            $card_icon = get_field('card_icon', $item->ID);
                            if ($card_icon) : ?>
                                <div class="city-card-icon">
                                    <img src="<?php echo esc_url($card_icon['url']); ?>" alt="<?php echo esc_attr($card_icon['alt']); ?>" />
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="city-card-content">
                        <?php if (is_singular('escuelas')) : ?>
                            <a href="<?php echo esc_url(get_permalink($item->ID)); ?>" class="content-image">
                                <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'img/arrow-right-solid.svg'); ?>" alt="">
                            </a>
                        <?php endif; ?>
                        <h3><?php echo esc_html($item->post_title); ?></h3>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($item->ID), $excerpt_length, '')); ?></p>
                        <a href="<?php echo esc_url(get_permalink($item->ID)); ?>" class="read-more-link">
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


function display_testimonial_heading_with_title() {
    $testimonial_heading = get_field('testimonial_heading');
    $post_title = get_the_title();

    if ($testimonial_heading) {
        return '<h2 class="testimonial-title">' . esc_html($testimonial_heading) . ' <span class="highlight-title">' . esc_html($post_title) . '</span></h2>';
    } else {
        return '';
    }
}
add_shortcode('testimonial_heading', 'display_testimonial_heading_with_title');



function get_programs_with_hover_cards() {
    // Start output buffering
    ob_start();

    // Get the list of cities
    $programs = get_field( 'programs' ); 
    if ($programs && is_array($programs)) : ?>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper"><?php
                foreach ($programs as $program) : ?>
                    <div class="swiper-slide">
                        <a href="<?php echo esc_url(get_permalink($program->ID)); ?>" class="city-card">
                            <div class="city-card-image"><?php
                                $image_url = get_the_post_thumbnail_url($program->ID); ?>
                                <img class="citi-image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($program->post_title); ?>">
                                <h3><?php echo esc_html($program->post_title); ?></h3>
                                <div class="city-card-icon">
                                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'img/arrow-right-solid.svg'); ?>" alt="">
                                </div>
                            </div>
                            <div class="city-card-content">
                                <h3><?php echo esc_html($program->post_title); ?></h3>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt($program->ID), 8, '')); ?></p>
                            </div>
                        </a>
                    </div><?php
                endforeach; ?>
            </div>
        </div><?php
    endif;
    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('programs_with_hover_cards', 'get_programs_with_hover_cards');


function get_testimonials() {
    // Start output buffering
    ob_start();

    // Get the list of cities
    $testimonials = get_field( 'testimonials' );

    if ($testimonials && is_array($testimonials)) : ?>
        <div class="swiper myTestimonials">
            <div class="swiper-wrapper"><?php

                foreach ($testimonials as $testimonial) : 
                    // Fetch the testimonials meta
                    $testimonial_meta = get_post_meta($testimonial->ID, 'immigro_testimonials_mb_settings', true);
                    $author_name = isset($testimonial_meta['author_name']) ? $testimonial_meta['author_name'] : '';
                    $author_job_position = isset($testimonial_meta['author_job_position']) ? $testimonial_meta['author_job_position'] : '';
                    $author_rating_value = isset($testimonial_meta['author_rating_value']) ? $testimonial_meta['author_rating_value'] : '';
                    $author_text = isset($testimonial_meta['author_text']) ? $testimonial_meta['author_text'] : '';
                    $truncated_text = wp_trim_words($author_text, 20, '...');
                    $image_url = get_the_post_thumbnail_url($testimonial->ID);

                    if (empty($author_text)) {
                        continue;
                    }
                    ?>
                    
                    <div class="swiper-slide">
                        <div class="isotope-item">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <div class="content-box">
                                        <span class="icon fa fa-quote-left" aria-hidden="true"></span>
                                        <div class="rating">
                                            <div class="star-rating">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $author_rating_value) {
                                                        echo '<i class="fa fa-star" style="color: #fa8714;"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-star-o" style="color: #fa8714;"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="author-text"><?php echo esc_html($truncated_text); ?></div>
                                    </div>
                                    <div class="info-box">
                                        <div class="thumb">
                                            <img width="300" height="300"  src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>" class="img-fullwidth wp-post-image">			
                                        </div>
                                        <h5 class="name"><?php echo esc_html($author_name); ?></h5>
                                        <span class="job-position"><?php echo esc_html($author_job_position); ?></span>
                                    </div>
                                </div>
                            </div>                
                        </div>
                    </div><?php
                endforeach; ?>
                
            </div>
        </div><?php
    endif; 
    
    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('all_testimonials', 'get_testimonials');


function list_services_dream_section() {
    // Start output buffering
    ob_start();

    // Get the list of cities
    if (have_rows('services_of_dream_section')): ?>
        <ul class="dream-list">
            <?php while (have_rows('services_of_dream_section')): the_row(); 
                $heading = get_sub_field('heading_dream_country'); ?>
                <li><i class="fa-solid fa-circle-check"></i> <?php echo esc_html($heading); ?></li>
            <?php endwhile; ?>
        </div>
    <?php endif;
    
    // Return the content as a string
    return ob_get_clean();
}
add_shortcode('list_dream_services', 'list_services_dream_section');