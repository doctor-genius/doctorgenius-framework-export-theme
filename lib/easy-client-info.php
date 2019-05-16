<?php
function display_address( $args = NULL ) {
    // Load global FW options
    $fw_options = get_option( 'dg_options' );

    $defaults = array(
        'line_breaks'               => TRUE,
        'pre_output_markup'         => NULL,
        'post_output_markup'        => NULL,
        'use_address_element'       => TRUE,
        'multilocation_dropdown'    => FALSE
    );
    $args = wp_parse_args( $args, $defaults );

    $output = NULL;

    if ( ! $args['multilocation_dropdown'] ) {
        $address_line_1 = !isset( $fw_options['company_address_line_1'] ) ? 'No address found' : $fw_options['company_address_line_1'];
        $address_line_2 = !isset( $fw_options['company_address_line_2'] ) ? 'No address found' : $fw_options['company_address_line_2'];

        $output .= $address_line_1;
        if ( $args['line_breaks'] === TRUE ) {
            $output .= '<br>';
        } else {
            $output .= ' ';
        }
        $output .= $address_line_2;

        if ( $args['use_address_element'] ) {
            $args['pre_output_markup']  = $args['pre_output_markup'] . '<address class="client-address">';
            $args['post_output_markup'] = '</address>' . $args['post_output_markup'];
        }
        $output = $args['pre_output_markup'] . $output . $args['post_output_markup'];
    }
    else {
        // Not yet implemented @todo#6
    }

    return $output;
}

function display_phone( $args = NULL ) {
    // Load global FW options
    $fw_options = get_option( 'dg_options' );
    $phone_number = !isset( $fw_options['company_phone'] ) ? 'No Phone # Found' : $fw_options['company_phone'];

    $defaults = array(
        'click_to_call' => TRUE,
        'button_classes' => NULL,
        'button_link' => 'default',
        'pre_inner_markup' => NULL,
        'post_inner_markup' => NULL,
        'custom_inner_markup' => NULL,
        'pre_output_markup' => NULL,
        'post_output_markup' => NULL,
        'phone_number' => $phone_number
    );

    $args = wp_parse_args( $args, $defaults );

    $unformatted_phone = $args['phone_number']; //Unformatted

    //Process with proper display characters
    $formatted_phone = '(' . substr( $unformatted_phone, 0, 3 ) . ') ' . substr( $unformatted_phone, 3, 3) . '-' . substr( $unformatted_phone, 6, 4);
    $ctc_formatted_phone = NULL;
    if ( $args['click_to_call'] === TRUE ) {
        $ctc_formatted_phone =  'tel:+1-' . substr( $unformatted_phone, 0, 3 ) . '-' . substr( $unformatted_phone, 3, 3) . '-' . substr( $unformatted_phone, 6, 4);
    }

    $output = NULL;

    if ( $args['click_to_call'] === FALSE ) {
        $output =  $formatted_phone;
    }
    else {
        $output =  '<a ';
        if ( $args['button_classes'] ) { $output .= 'class="' . $args['button_classes'] . '" '; }
        
        if ( $args['button_link'] == 'default' ) { 
            $output .= ' href="'. $ctc_formatted_phone . '" '; 
        }
        else {  
            $output .= ' href="'. $args['button_link'] .'" '; 
        }
        $output .= ' title="Click to call '. $fw_options['company_name']  . ' at ' . $formatted_phone . '"';
        $output .= '>';

        if ( $args['custom_inner_markup'] ) {
            $output .= $args['pre_inner_markup'] . $args['custom_inner_markup'] . $args['post_inner_markup'];
        }
        else {
            $output .= $args['pre_inner_markup'] . '<span class="client-phone">' . $formatted_phone . '</span>' . $args['post_inner_markup'];
        }
        $output .= '</a>';
    }

    return $args['pre_output_markup'] . $output . $args['post_output_markup'] ;
}

/**
 * @param $args - see $defaults
 *
 * @return string
 */
function display_company_name( $args ) {
    // Load global FW options
    $fw_options = get_option( 'dg_options' );
    $company_name = !isset( $fw_options['company_name'] ) ? 'Company name not found' : $fw_options['company_name'];

    $defaults = array(
        'inner_markup'      =>      $company_name,          // Used to override default company name
        'pre_output_markup' =>      NULL,                   // Used to wrap the output in some arbitrary markup
        'post_output_markup' =>     NULL,
    );

    $args = wp_parse_args( $args, $defaults );
    $output = NULL;

    $output .= $args['pre_output_markup'] . '<span class="client-company-name">' . $args['inner_markup'] . '</span>' . $args['post_output_markup'];

    return $output;
}
