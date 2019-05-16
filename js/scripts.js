(function($) {

/*
    jQuery(document).ready(function(){


        jQuery('.dropdown').dblclick(function(e){

            e.preventDefault();
            e.stopPropagation();
            jQuery('.dropdown').unbind();


        });
        
        jQuery('.dropdown').click(function(e){

            
            e.stopPropagation();
            jQuery('.dropdown').unbind();
            
            
        });

    });*/

/*
    jQuery(document).ready(function(){

        var clickDisabled = false;
        jQuery('.dropdown').click(function(e){
            console.log('clicked');

            if (clickDisabled) {
                console.log('clicked but disabled ' + clickDisabled);
                e.preventDefault();
                e.stopPropagation();
                jQuery('.dropdown').unbind();
                return;
            }
            e.stopPropagation();
            clickDisabled = true;

            // do your real click processing here

            //setTimeout(function(){clickDisabled = false;}, 2000000);
        });

    });
*/


    /*

    // main dropdown initialization
    $('.dropdown-button.main-menu-item').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: true, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        belowOrigin: true, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });
// nested dropdown initialization
    $('.dropdown-button.sub-menu-item').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: ($('.dropdown-content').width() * 3) / 3.05 + 3, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });
    */
    
$(document).on("scroll", function () {

    if ($(document).scrollTop() > 200) {
        $("nav").removeClass("large").addClass("small");
    } else {
        $("nav").removeClass("small").addClass("large");
    }
});

$(function () {
    $(window).scroll(function () {
        if ($(document).scrollTop() > 200) {
            $('.sticky-footer').addClass("show");
        }
        else {
            $('.sticky-footer').removeClass("show");
        }
    });

});

/* New: */
$('.single-location-hero').slick({
    arrows: false,
    dots: true,
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3
});


$('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 0,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: true, // Displays dropdown below the button
        alignment: 'left', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
);

$('.multi-location-nav').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
);

$('.multi-phone').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'right', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
);

$('.footer-multi-location').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'right', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
);

$('.awda-button').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
);

$('.button-collapse').sideNav({
    menuWidth: 240, // Default is 240
    edge: 'right', // Choose the horizontal origin
    closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
});

$('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .8, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
    }
);

// Main Slider //
$('.slider').slider({
    indicators: true,
});

$('.slider').slider('pause'); //just pause the slider once its loaded

$('.reviews-callout-slider').slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    adaptiveHeight: false,
    autoplay: true,
    speed: 500,
    fade: true,
    cssEase: 'linear',
});


$('.center').slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 3,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ]
});

$('.testimonials-slider').slick({
    centerMode: true,
    autoplay: false,
    centerPadding: '400px',
    arrows: false,
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 3,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '100px',
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '10px',
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '10px',
            }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ]
});
    
$(document).ready(function () {
    $('.matchInnerHeight').matchHeight(  );
    /* $('select').material_select();
    Materialize.updateTextFields(); */
    $('.matchHeight').matchHeight();
    
});

})( jQuery );
