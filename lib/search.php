<?php 

add_filter( 'get_search_form', 'dg_custom_search_form' );
// Allows for a custom search form, in a nonostandard location (normally in root WP directory)
function dg_custom_search_form( $form_instance ) {
	ob_start();
	$output = NULL;
	
	$form_path =  get_template_directory() .  '/partials/searchform.php';
	require( $form_path );

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
	
}

// [searchform]
add_shortcode( 'searchform', 'dg_custom_search_form_shortcode');
function dg_custom_search_form_shortcode(){
    static $search_form_instance;
    if ( empty( $search_form_instance )  ) { $search_form_instance = 1; }
    
    $search_form_instance++;
    
    
	return dg_custom_search_form( $search_form_instance );
}
