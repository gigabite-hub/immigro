"use strict";

(function ($) {

    $(document).ready(function () {

        console.log('ready');
        var currentURL = window.location.href;
        console.log(currentURL);

        $('.faq-heading').click(function () {
            $(this).next('.faq-description').slideToggle();
            $(this).toggleClass('active');
        });

        // Common Swiper settings
        var swiperSettings = {
            slidesPerView: 3,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: '.navigation-right',
                prevEl: '.navigation-left',
            },
            breakpoints: {
                480: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                }
            }
        };

        new Swiper(".mySwiper", swiperSettings);

        // Add autoplay settings for testimonials swiper
        swiperSettings.autoplay = {
            delay: 2500,
            disableOnInteraction: true,
        };

        new Swiper(".myTestimonials", swiperSettings);

    });

}(jQuery));
