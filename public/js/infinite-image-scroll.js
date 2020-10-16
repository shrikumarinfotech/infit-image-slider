// function for infinite image scroll
'use strict';

jQuery.noConflict();
jQuery(document).ready(function( $ ){
    // Continious Image Carousel: Working Version
    function imageInfiniteCarouselAnimate(){
        // Define the elements for carousel
        const dataDiv = $('.module-carousel-wrapper');
        let dataDivContent = $(dataDiv).find('.module-carousel-slides');
        let dataDivContentLength = dataDivContent.length;
        // Define window width
        let totalWindowWidth = $(window).width();
        $(window).on('load resize', function(){
            totalWindowWidth = $(window).width();
        });
        // Append duplicate div elements to fill the content area
        function carouselLoop(){
            if( dataDivContentLength < totalWindowWidth){ // *minimum slide number is 6: important
                for( let i = 0; i < 3; i++){ // * number of copies can be opted from html dynamically
                    var dataDivContentNew = $(dataDiv).html();
                    $(dataDiv).append(dataDivContentNew);
                }
            }
        }
        carouselLoop();
        // Define device width
        let dataDivWidth;
        dataDiv.promise().done(function(){
            // re-assign values
            dataDivContent = $(dataDiv).find('.module-carousel-slides');
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
                    duration: 20000, // Animation duration * can be opted from html dynamically
                    easing: 'linear',
                    complete: function(){
                        // console.log('animation complete');
                        // Reset the position after animation is completed
                        dataDiv.css('left', 0);
                    }
                });
            }, 1000); // 1000 is interval duration
        });
    }
    // Check if carousel module exists in DOM
    if($('.module-cont-img-carousel').length !== 0){
        // If yes, run the funtion
        imageInfiniteCarouselAnimate();
    }
});