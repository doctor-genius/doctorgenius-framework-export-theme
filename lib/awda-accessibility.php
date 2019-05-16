<?php
// Tweak CF7's output to make more screenreader-accessible
add_filter( 'wpcf7_form_elements', 'cf7_accessibility_filter' );
function cf7_accessibility_filter( $content ) {
    //Track the # of times the form is loaded anywhere on a page
    static $global_count = 0;
    $global_count += 1;

    //Get the input element's name and add a unique ID to each form <label>
    $label_find = '/for="(.*?)"/';
    $id = 'id="$1-' . $global_count . '"';
    $content = preg_replace( $label_find, $id, $content );

    //Associate the input/textarea/select with that ID
    $input_find = '/(<(input|textarea|select).*?name="(.*?))"/';
    $input_replace = '$1" aria-labelledby="$3-' . $global_count . '"';
    $content = preg_replace( $input_find, $input_replace, $content );

    return $content;
}


if ( ! $fw_options ) { $fw_options = get_option('dg_options'); } 

if ( !empty( $fw_options['awda_toggle'] ) ) {
    	
	//Add a button to the footer
	add_action( 'wp_footer', 'insert_awda_button_markup' );
    
}

function insert_awda_button_markup() { 
	?>
    <section class="awda-sticky hide-on-small-only">
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light hoverable black">
                <i class="fa fa-wheelchair"></i>
            </a>
            <ul>
                <li><a class="black-text-activator btn-floating waves-effect waves-light white"><i class="fa fa-font" aria-hidden="true"></i></a></li>
                <li><a class="white-text-activator btn-floating waves-effect waves-light black"><i class="fa fa-font" aria-hidden="true"></i></a></li>
                <li><a class="font-increase-activator btn-floating waves-effect waves-light black"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                <li><a class="font-decrease-activator btn-floating waves-effect waves-light black"><i class="fa fa-minus" aria-hidden="true"></i></a></li>
                <li><a class="font-size-reset btn-floating waves-effect waves-light black"><i class="fa fa-refresh" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </section>

    
    <?php
}

function insert_awda_button_markup_mobile(  ) {
    ?>
    <div class="awda-sticky mobile">
        <div class="fixed-action-btn click-to-toggle"  title="Access Accessibility Options">
            <a class="btn-floating btn-large waves-effect waves-light black">
                <i class="fa fa-wheelchair"></i>
                <span class="icon-text">Access Accessibility Options</span>
            </a>
            <ul>
                <li><a class="black-text-activator btn-floating waves-effect waves-light black"><i class="fa fa-adjust" aria-hidden="true"></i><span class="icon-text">Activate Black Text</span></a></li>
                <li><a class="white-text-activator btn-floating waves-effect waves-light black"><i class="fa fa-font" aria-hidden="true"></i><span class="icon-text">Activate White Text</span></a></li>
                <li><a class="font-increase-activator btn-floating waves-effect waves-light black"><i class="fa fa-plus" aria-hidden="true"></i><span class="icon-text">Increase Font Size</span></a></li>
                <li><a class="font-decrease-activator btn-floating waves-effect waves-light black"><i class="fa fa-minus" aria-hidden="true"></i></a><span class="icon-text">Decrease Font Size</span></li>
                <li><a class="font-size-reset btn-floating waves-effect waves-light black"><i class="fa fa-refresh" aria-hidden="true"></i><span class="icon-text">Reset Accessibility Options</span></a></li>
            </ul>
        </div>
    </div>
    <?php 
}
