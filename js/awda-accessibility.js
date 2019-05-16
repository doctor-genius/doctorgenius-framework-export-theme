var awdaDebug = true;
var textColorCookieName = "DGAccessibilityTextColor";
var textSizeCookieName = "DGAccessibilityTextSize";

jQuery(document).ready(function( $ ) {
    //setCookie(textSizeCookieName, 100);

    /* Text Color Controls */
    $('.black-text-activator').click( { color: 'black'}, AccessibilityTextColorHandler );
    $('.white-text-activator').click( { color: 'white'}, AccessibilityTextColorHandler );

    /* Text Size Controls */
    $('.font-increase-activator').click( { multiplierDelta: 10 }, ChangeAccessibilityTextSize );
    $('.font-decrease-activator').click( { multiplierDelta: -10 }, ChangeAccessibilityTextSize );
    $('.font-size-reset').click( {}, resetFontAccessibility );
   
    //Read and Apply Cookies
    var accessibilityTextColorCookie = getCookie( textColorCookieName );
    if ( accessibilityTextColorCookie ) {
        if ( awdaDebug ) { console.log( "Accessibility Text Mode Loaded: " + accessibilityTextColorCookie + " text on contrast background." ); }
        ApplyAccessibilityTextColor( accessibilityTextColorCookie ); 
    }

    var accessibilityTextSizeCookie = parseInt( getCookie( textSizeCookieName ) );
    if ( accessibilityTextSizeCookie ) {
        if ( awdaDebug ) { console.log( "Accessibility Text Mode Loaded: " + accessibilityTextSizeCookie + "% font size." ); }
        ApplyAccessibilityTextSize();
    }
});


function ChangeAccessibilityTextSize( e ) {
    // Get text nodes
    var textNodes = findTextNodes( 'body *' );
    
    //Cache previous text percent, used to calculate 'original' text size when changing
    var previousTextPercent = parseInt( getCookie(textSizeCookieName) ? getCookie(textSizeCookieName) : '100' );
    var previousTextSizeMultiplier = previousTextPercent * .01;

    //Calculate new target text size multiplier based on button click
    var textPercentDelta = parseInt( e.data.multiplierDelta );
    var newTextPercent = previousTextPercent + textPercentDelta;
    var newTextSizeMultiplier = newTextPercent * .01 ;

    
    console.log( "Button pressed. Before button, text percent was " + previousTextPercent + " so to find original text size we divide current text size by " + previousTextSizeMultiplier 
        + ".  New text percent is " + newTextPercent + " so to find new text size we multiply original text size by " + newTextSizeMultiplier ); 
    
    
    jQuery.each( textNodes, function() {
        // Find the node's encapsulating element
        var textElement = jQuery(this).parent();

        //Calculate original node text size
        var currentTextSize = parseFloat( textElement.css('font-size') );
        var originalTextSize = currentTextSize / previousTextSizeMultiplier;
        
        //Calculate and apply new node text size
        var newTextSize = originalTextSize * newTextSizeMultiplier;
        textElement.css( 'font-size', newTextSize + "px" );

        // If larger than 100%, also apply bold font weight
        if ( newTextSizeMultiplier > 1 ) {
            textElement.css('font-weight', 'bold');
        } else {
            textElement.css('font-weight', '');
        }
        
    });

    //Finally, preserve new text % as a cookie for future use
    setCookie( textSizeCookieName, newTextPercent );
    
}

//@todo#74 this could be combined with the logic in the change function
function ApplyAccessibilityTextSize() {
    var textSizePercent = parseInt( getCookie(textSizeCookieName) ? getCookie(textSizeCookieName) : '100' );
    var textSizeMultiplier = textSizePercent * .01 ;
    var textNodes = findTextNodes( 'body *' );
    
    if ( awdaDebug ) { console.log( 'Applying Text Size from cookie: ' + parseInt( textSizePercent ) + '%.' ); }

    jQuery.each( textNodes, function( index ) {

        // Find the node's encapsulating element
        var textElement = jQuery(this).parent();
        var currentTextSize = parseFloat( textElement.css('font-size') );
        var newTextSize = parseFloat( currentTextSize * textSizeMultiplier );

        if ( textSizeMultiplier > 1 ) {
            textElement.css('font-weight', 'bold'); 
        } else {
            textElement.css('font-weight', '');
        }
        
        textElement.css( 'font-size', newTextSize + 'px' );

    });

}


function AccessibilityTextColorHandler( e ) {
    var eventColor = e.data.color;

    if ( getCookie(textColorCookieName) !== eventColor ) {
        ApplyAccessibilityTextColor( eventColor );
        var accessibilityBackgroundColor = ( eventColor === 'white' ) ? 'black' : 'white';
        if ( awdaDebug ) { console.log( 'Accessibility Text Color Mode Activated: ' + eventColor + ' text on ' + accessibilityBackgroundColor + ' background.' ); }
    }
    else {
        if (awdaDebug) { console.log( "Accessibility Text Mode: " + eventColor + " already activated. Button click ignored." ); }
    }
    
}


function ApplyAccessibilityTextColor( AccessibilityTextColor ) {
   
    if ( ! setCookie( textColorCookieName, AccessibilityTextColor ) ) { 
        console.log( "Error creating local cookie. AwDA Accessibility will not persist.  Please enable cookies for full accessibility." );
    }

    var accessibilityBackgroundColor = (AccessibilityTextColor === 'white') ? 'black' : 'white';

    var textNodes = findTextNodes('body *');
    textNodes.each(function () {

        // Color the text of each node's parent (ie, each actual element) correctly, and save it for background coloring
        var textColorElements = jQuery(this).parent();

        textColorElements = textColorElements.add(jQuery('select')).add(jQuery('input')).add(jQuery('.sticky-footer .col .btn-floating i'));
        textColorElements.css('color', AccessibilityTextColor);

        //Add one level of parents to the backgrounds list
        var backgroundElements = textColorElements.add(textColorElements.parent())
            .add(textColorElements.parent().parent())
            // Also add all our fw color classes
            .add(jQuery(".bg-primary, .bg-secondary, .bg-tertiary, .btn-tertiary, .bg-mute"));


        //Color the backgrounds
        backgroundElements.css('background-color', accessibilityBackgroundColor);
        //@todo Check with dre re: border color on :after accents
    });

    
}


function setCookie(cname, cvalue, exdays) {
    //Handle cookie expiration
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    
    //Create the actual cookie with root path on the site
    try {
        document.cookie = encodeURIComponent(cname) + "=" + cvalue + ";" + expires + ";path=/";
    } catch(e) {
        if (awdaDebug) { console.log("Error setting local cookie."); }
        return false; 
    }
    return true;
}

function getCookie(cname) {
    //Read in the document cookies
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    
    //Cookie formatting
    var name = cname + "=";
    
    //Search the entire cookie string for the supplied cookie name
    for ( var i = 0; i < ca.length; i++ ) {
        var c = ca[i];
        while ( c.charAt(0) === ' ' ) {
            c = c.substring(1);
        }
        if ( c.indexOf(name) === 0 ) {
            return c.substring(name.length, c.length);
        }
    }
    return false;
}

function findTextNodes( startingSelector ) {
    return jQuery( startingSelector ).not('iframe').contents().filter(function () {
        return (this.nodeType == 3) && this.nodeValue.match(/\S/);
    });
}

function resetFontAccessibility(){
    if ( awdaDebug ) { console.log( 'Resetting Text Size and color to Original'); }
    
    //Finally, reset cookies
    setCookie(textSizeCookieName, '');
    setCookie(textColorCookieName, '');
    
    var textNodes = findTextNodes( 'body *' );
    jQuery.each( textNodes, function() {
        
        // Find the node's encapsulating element
        var textElement = jQuery(this).parent();
        textElement.css( 'font-size', '' );
        textElement.css( 'font-weight', '' );

        var textColorElements = textElement.add(jQuery('select')).add(jQuery('input')).add(jQuery('.sticky-footer .col .btn-floating i'));
        textColorElements.css('color', '');

        //Add one level of parents to the backgrounds list
        var backgroundElements = textColorElements.add(textColorElements.parent())
            .add(textColorElements.parent().parent())
            // Also add all our fw color classes
            .add(jQuery(".bg-primary, .bg-secondary, .bg-tertiary, .btn-tertiary, .bg-mute"));


        //Color the backgrounds
        backgroundElements.css('background-color', '');        
        
    });

}
