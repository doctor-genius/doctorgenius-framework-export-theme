<?php
/**
 * Helper functions
 */


/** Bulk add an array of filters to a filter */
function add_filters($tags, $function) {
	foreach($tags as $tag) {
		add_filter($tag, $function);
	}
}

/**
 * @param $array
 * Take an array of mixed associative and indexed values, converting any indexed values to new elements with the value as key and boolean TRUE as the value
 * Designed to process shortcode attributes with mixed parameter usages
 *
 *      eg, allowing    [shortcode_name attrib_1="Has a parameter" attrib_2] 
 *      instead of      [shortcode_name attrib_1="Has a parameter" attrib_2="TRUE"]
 * 
 * @return mixed
 */
function convert_shortcode_paramaterless_attributes_to_associative_array_with_boolean_value( $array ) {
	foreach( $array as $key => $value ) {
		if ( is_numeric( $key ) ) {
			$array[$value] = TRUE;
			unset( $array[$key] );
		}
	}
	return $array;
}


/**
 * Returns a readable string from a delimited lowercase slug 
 * @param $str
 * @param string $delimiter
 *
 * @return string
 */
function slug_to_readable($str, $delimiter = '-'){
	return ucwords( str_replace( $delimiter, ' ', $str ) );
}


/**
 * @param $meta_options_list array:
 *                                  string option name
 *                                  string sanitization type 
 */
function dg_process_postmeta( $meta_options_list ) {
	$meta = array();
	
	// Save each piece of post meta, filtering via the input_type, and sanitizing as appropriate
	foreach ( $meta_options_list as $option => $input_type ) {
		switch ( $input_type ) {
			case 'text' :
				$meta[ $option ] = isset( $_POST['meta'][ $option ] ) ? esc_textarea( $_POST['meta'][ $option ] ) : '';
				break;

			case 'checkbox' :
				$meta[ $option ] = isset( $_POST['meta'][ $option ] ) ? $_POST['meta'][ $option ] : '';
				if ( $meta[ $option ] !== '1' && $meta[ $option ] !== '0' ) {
					$meta[ $option ] = '0';
				}
				break;

			case 'textarea' :
				$meta[ $option ] = isset( $_POST['meta'][ $option ] ) ? esc_textarea( $_POST['meta'][ $option ] ) : '';
				break;
		}
	}
	
	return $meta;
}

// Removes any hardcoded width or height attributes from standard <img> markup
function strip_img_dimensions($img_markup) {
	$filter_out = array (
		'/\swidth=["\'].*?["\']/',
		'/\sheight=["\'].*?["\']/',
	);
	$replacements = array (
		'',
		'',
	);
	$img_markup = preg_replace( $filter_out, $replacements, $img_markup  );
	return $img_markup;
}

// Comparison function for reordering gallery arrays
function reorder_gallery_items( $a, $b ) {

	if ($a == $b) {
		return 0;
	}

	return ( $a['order'] < $b['order'] ) ? -1 : 1 ;
}


// Augments array_push to preserve associative key
function array_push_assoc($array, $key, $value){
    $array[ $key ] = $value;
    return $array;
}

// get_post_meta returns values as an array even if only one key exists. This flattens unnecessarily arrayed data 
function flatten_postmeta( $postmeta_array ){
    $return_array = array();
    foreach( $postmeta_array as $key => $val ){
        
        if( is_array( $val ) && count( $val ) == 1) {
            $return_array = array_push_assoc( $return_array, $key, $val[0]);
        } else {
            $return_array = array_push_assoc( $return_array, $key, $val);
        }
    }
    return $return_array;
}


 
//only returns a single value for any queried postmeta
function get_post_meta_single( $id ) { 
    if ( !$id ) { $id = get_the_id(); }
    
    $meta = (array) get_post_meta( $id );
    
    if ( $meta ) {
        $return_array = array();
        foreach ( $meta as $key => $val ) {
            $return_array = array_push_assoc( $return_array, $key, $val[0] );
        }
        return $return_array;
    }
}

function filter_dgpostmeta( $postmeta_array ) {
    if ( in_array( 'dg_postmeta', array_keys( $postmeta_array ) ) ) {
        $unwanted_meta = unserialize( $return_array['dg_postmeta'] );

        foreach ( $unwanted_meta as $key => $val ) {

            $postmeta_array[ $key ] = $val;

        }
        //unset( $meta_array['dg_postmeta'] );
    }
    return $postmeta_array;
}


function retrieve_google_fonts() {

    
    if ( GOOGLEAPIKEY ) { $key = GOOGLEAPIKEY; }

    $fonts_request_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $key ;
    
    $fonts = array();
    
    $fonts['test'] = array( 'name' => 'Test Name', 'slug' => 'test-name', 'url' => 'http://something.com' );
    $fonts['test2'] = array( 'name' => 'Test2 Name', 'slug' => 'test2-name', 'url' => 'http://something.com' );
    $fonts['test3'] = array( 'name' => 'Test3 Name', 'slug' => 'test2-name', 'url' => 'http://something.com' );
    
    return $fonts;
    
    
}

add_action( 'wp_ajax_retrieve_googlefonts', 'dg_retrieve_googlefonts' );

function dg_retrieve_googlefonts() {
    // Handle request then generate response using WP_Ajax_Response
    error_log( 'AJAX handler called via hook' );
    
    // Don't forget to stop execution afterward.
    wp_die();
}


if ( !function_exists('intdiv')  ) {
    function intdiv($dividend, $divisor) {
        $quotient = ($dividend - $dividend % $divisor) / $divisor;

        return $quotient;
    }
}
