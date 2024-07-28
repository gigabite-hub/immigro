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

    });

}(jQuery));
