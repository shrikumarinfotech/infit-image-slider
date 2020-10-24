// function for infinite image scroll
'use strict';

jQuery.noConflict();
jQuery(document).ready(function( $ ){
    // INFTH Image Slider: Working Version
    function infthImageSliderAnimate(){
        // Check if speed is defined/delivered and validate or set the default value
        let optionSpeed = parseInt($.trim( $('.infth-image-slider-field-speed').html() ));
        if(optionSpeed === undefined || optionSpeed === null || optionSpeed === NaN){
            // if not defined set default value
            optionSpeed = 20000;
        }
        else{
            // if not within limit set to default value
            if( optionSpeed < 1000 || optionSpeed > 50000 ){
                optionSpeed = 20000;
            }
            // else deliver given value
            optionSpeed = optionSpeed;
        }
        // Define the elements for slider
        const dataDiv = $('.module-infth-image-wrapper');
        let dataDivContent = $(dataDiv).find('.module-infth-image-slides');
        let dataDivContentLength = dataDivContent.length;
        // Define window width
        let totalWindowWidth = $(window).width();
        $(window).on('load resize', function(){
            totalWindowWidth = $(window).width();
        });
        // Append duplicate div elements to fill the content area
        function sliderLoop(){
            if( dataDivContentLength < totalWindowWidth){ // *minimum slide number is 6: important
                for( let i = 0; i < 3; i++){ // * number of copies can be opted from html dynamically
                    var dataDivContentNew = $(dataDiv).html();
                    $(dataDiv).append(dataDivContentNew);
                }
            }
        }
        sliderLoop();
        // Define device width
        let dataDivWidth;
        dataDiv.promise().done(function(){
            // re-assign values
            dataDivContent = $(dataDiv).find('.module-infth-image-slides');
            dataDivContentLength = dataDivContent.length;
            dataDivWidth = dataDivContent.width() * ( dataDivContentLength / 2 );
        }); // After appending is done get the width of two(2) elements or
            // half (1/2) of total set of image slides
        // Animate with an interval(loop)
        dataDiv.promise().done(function(){
            // Set interval and loop
            setInterval(function(){
                $(dataDiv).animate({
                    left: -dataDivWidth
                }, {
                    duration: optionSpeed, // Animation duration * can be opted from html dynamically. Default 20000.
                    easing: 'linear',
                    complete: function(){
                        // Reset the position after animation is completed
                        dataDiv.css('left', 0);
                    }
                });
            }, 1000); // 1000 is interval duration
        });
    }
    // Check if slider module exists in DOM
    if($('.module-infth-image-slider').length !== 0){
        // If yes, run the funtion
        infthImageSliderAnimate();
    }
});