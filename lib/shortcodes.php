<?php

/**
 * Provides a shortcode for use in posts, returning client's address with optional formatting
 * @usage [address (linebreaks | line_breaks | line-breaks)]
 *
 * @return string
 */
function client_address_shortcode( $atts ) {

    $atts = convert_shortcode_paramaterless_attributes_to_associative_array_with_boolean_value( (array) $atts );

    //Convert shortcode strings to function arguments and remove temp arrays
    if ( $atts['linebreaks'] ) { $atts['line_breaks'] = TRUE; unset( $atts['linebreaks']); }
    if ( $atts['line-breaks'] ) { $atts['line_breaks'] = TRUE; unset( $atts['line-breaks']); }

    if ( $atts['address-wrapper'] ) { $atts['use_address_element'] = TRUE; unset( $atts['address-wrapper']); }

    $atts['pre_output_markup'] = $atts['pre'];
    $atts['post_output_markup'] = $atts['post'];
    unset( $atts['pre'] );
    unset( $atts['post'] );

    // Merge defaults with supplied arguments
    $atts = shortcode_atts( array(
        'pre_output_markup' => NULL,
        'post_output_markup' => NULL,
        'line_breaks' => FALSE,
        'use_address_element' => FALSE
    ), $atts, 'address' );

    // Retrieve output with processed attributes
    return display_address( $atts );
}
add_shortcode( 'address', 'client_address_shortcode' );


/**
 * Provides a shortcode for use in posts, returning client's main phone # with optional formatting
 * @usage [phone (ctc | CTC | click_to_call | click-to-call)]
 *
 * @return string
 */
function client_phone_shortcode( $atts ) {

    $atts = convert_shortcode_paramaterless_attributes_to_associative_array_with_boolean_value( (array) $atts );

    //Convert shortcode strings to function arguments and remove temp arrays
    if ( $atts['ctc'] ) { $atts['click_to_call'] = TRUE; unset( $atts['ctc']); }
    if ( $atts['CTC'] ) { $atts['click_to_call'] = TRUE; unset( $atts['CTC']); }
    if ( $atts['click-to-call'] ) { $atts['click_to_call'] = TRUE; unset( $atts['click-to-call']); }

    $atts['pre_output_markup'] = $atts['pre'];
    $atts['post_output_markup'] = $atts['post'];
    unset( $atts['pre'] );
    unset( $atts['post'] );

    // Merge defaults with supplied arguments
    $atts = shortcode_atts( array(
        'click_to_call'         => FALSE,
        'pre_output_markup'     => NULL,
        'post_output_markup'    => NULL,
        'button_classes'        => NULL
    ), $atts, 'phone' );

    // Retrieve output with processed attributes
    return display_phone( $atts );
}
add_shortcode( 'phone', 'client_phone_shortcode' );



/**
 * Provides a shortcode for use in posts, returning client's company's name with optional formatting
 * @usage [client_company_name (pre="") (post="") (bold) (italic)]
 *
 * @return string
 */
function client_company_name_shortcode( $atts ) {

    $atts = convert_shortcode_paramaterless_attributes_to_associative_array_with_boolean_value( (array) $atts );

    //Convert shortcode strings to function arguments and remove temp arrays
    $atts['pre_output_markup'] = $atts['pre'];
    $atts['post_output_markup'] = $atts['post'];
    unset( $atts['pre'] );
    unset( $atts['post'] );

    if ( $atts['bold'] ) {
        $atts['pre_output_markup'] = $atts['pre_output_markup'] . '<b>';
        $atts['post_output_markup'] = '</b>' . $atts['post_output_markup'];
    }
    if ( $atts['italic'] ) {
        $atts['pre_output_markup'] = $atts['pre_output_markup'] . '<i>';
        $atts['post_output_markup'] = '</i>' . $atts['post_output_markup'];
    }

    $atts = shortcode_atts( array(
        'pre_output_markup'     => NULL,
        'post_output_markup'    => NULL,
        'bold'                  => FALSE,
        'italic'                => FALSE
    ), $atts, 'company_name' );



    // Retrieve output with processed attributes
    return display_company_name( $atts );

}
add_shortcode( 'company_name', 'client_company_name_shortcode' );



/**
 * Adds generic shortcodes for our default contact form
 *
 * Usage: [contact-form] OR [contact-form main]
 * @return string
 */
function contact_form_shortcode( $atts ) {

    $atts = convert_shortcode_paramaterless_attributes_to_associative_array_with_boolean_value( (array) $atts );

    $opt = get_option('dg_options');

    $output = '<div class="contact-form">';


    if ( $atts['main'] ) {
        $contact_form_title = empty( $opt['main_contact_form_heading'] ) ? 'Request An Appointment' : $opt['main_contact_form_heading'];

        $output .= '<div class="form-title bg-primary">' . $contact_form_title . '</div>';
        $output .= '<div class="inner-form-wrapper">';

        if ( $opt['locations_override'] ) { $output .= do_shortcode('[MultiLocationContactForm]'); }
        elseif ( $opt['main_contact_form'] ) { $output .= do_shortcode( $opt['main_contact_form'] ); }
        else { $output .= '<p>No valid shortcode found.</p>'; }

        $output .= '</div><!-- /.inner-form-wrapper -->';
    }
    elseif ( $atts['contact'] && $opt['contact_us_form'] ) {

        $contact_form_title = empty( $opt['contact_us_form_title'] ) ? 'Send Us A Message' : $opt['contact_us_form_title'];

        $output .= '<div class="clearfix"><div class="form-title text-contrast">' . $contact_form_title . '</div><div class="message-icon"><img alt="Contact Us" src="'. DGFWIMGDIR. '/icon-message.png"></div></div>';
        $output .= '<div class="inner-form-wrapper">';

        if ( $opt['main_contact_form'] ) {
            $output .= do_shortcode( $opt['contact_us_form'] );
        }
        else { $output .= '<p>No valid shortcode found.</p>'; }

        $output .= '</div><!-- /.inner-form-wrapper -->';

    }
    else {
        return 'Contact Form has not been defined in Genius Framework settings.';
    }
    $output .= '</div><!-- /.contact-form -->';

    return $output;
}
add_shortcode( 'contact-form', 'contact_form_shortcode' );
add_shortcode( 'contact_form', 'contact_form_shortcode' );



/**
 * Provides a shortcode for use in posts, returning client's company's name with optional formatting
 * @usage [client_company_name (pre="") (post="") (bold) (italic)]
 *
 * @return string
 */
function dg_review_star( $atts ) {
    // Read this in from an option
    $star_markup = '<li><img src="' . get_template_directory_uri() . '/img/icon-stars.png" alt="Rating Star"></li>';

    $atts = shortcode_atts( array(
        'count'     => NULL,
    ), $atts, 'company_name' );

    $output = NULL;
    for ( $i = 1; $i <= $atts['count']; $i++ ) {
        if ( $star_markup ) { $output .= $star_markup; }
        else { $output .= '⭐'; }
    }

    return $output;
}
add_shortcode( 'star', 'dg_review_star' );
add_shortcode( 'stars', 'dg_review_star' );


//[MultiLocationContactForm]
function mle_multilocationcontactform_shortcode( $args ){

    $defaults = array(
        'exclude_service_page_form_heading' => TRUE
    );
    $args = wp_parse_args( $args, $defaults );
    ob_start();

    ?>

    <div class="multilocation-contact-form header-forms">

        <?php if ( $args['heading_markup'] != "" ) { echo $args['heading_markup']; } ?>
        <?php if ( $args['exclude_service_page_form_heading'] === TRUE ) {
            //echo '<style>.sidebar-appt-form > #form-style-2 > h4, .sidebar-appt-form > #form-style-2 > p { display: none; }</style>';
        } ?>

        <div class="location-selector clearfix" >
            <span class="wpcf7-form-control-wrap your-location">
                <select name="your-location" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
                  <option value="picker" selected >Choose a Location</option>
                    <?php
                    $loop_args = array( 'post_type' => 'locations' );
                    $loop = new WP_Query( $loop_args );
                    while ( $loop->have_posts() ) {

                        $loop->the_post();

                        // Load CPT postmeta
                        $location = get_post_meta_single( get_the_id() );

                        $cf7_shortcode = wp_specialchars_decode( $location['contact_form'], ENT_QUOTES );
                        //echo '<p>cf7 Shortcode: ' . $cf7_shortcode . '</p>';
                        preg_match( '/id=\"(.*?)"/', $cf7_shortcode, $matches );
                        $cf7_id = $matches[1];
                        //echo '<p>cf7 ID: ' . var_export( $matches ) . '</p>';

                        echo '<option value="' . $cf7_id . '">' . get_the_title() . '</option>';

                    }

                    wp_reset_postdata();
                    ?>
                </select>
            </span>
        </div>

        <?php
        //Out put the forms via shortcode, with custom hidden attributes"
        $mle_form_count = 0;
        $forms_loop_args = array( 'post_type' => 'locations' );
        $forms_loop = new WP_Query( $forms_loop_args );

        while ( $forms_loop->have_posts() ) {

            $forms_loop->the_post();
            // Load CPT postmeta

            $location = get_post_meta_single( $post->ID );

            $cf7_shortcode =  wp_specialchars_decode( $location['contact_form'], ENT_QUOTES );


            preg_match( '/id=\"(.*?)"/', $cf7_shortcode, $matches );
            $cf7_id = $matches[1];


            if ($mle_form_count == 0 ) {
                $cf7_shortcode = preg_replace( '/]/', ' html_id="'. $cf7_id .'"]', $cf7_shortcode);
            }
            else {
                $cf7_shortcode = preg_replace( '/]/', ' html_id="'. $cf7_id .'" html_class="hidden"]', $cf7_shortcode);
            }

            $mle_form_count++;;

            echo do_shortcode( $cf7_shortcode );

        }

        wp_reset_postdata();
        ?>

    </div>
    <!-- /.appt-box -->

    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
add_shortcode( 'MultiLocationContactForm', 'mle_multilocationcontactform_shortcode' );



function rjfsearchform( $form ) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
        <div><label class="screen-reader-text" for="s">' . __('Search our site for:') . '</label>
        <input type="text" value="' . get_search_query() . '" name="s" id="s" />
        <input class="button" type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
        </div>
        </form>';
    return $form;
}
add_shortcode('rjfsearch', 'rjfsearchform');


function dgfw_img_dir() {
    if ( defined( DGFWIMGDIR ) ) {
        return DGFWIMGDIR;
    }
    else {
        $dgfw_uploads_dir = wp_get_upload_dir();
        return $dgfw_uploads_dir['url'];
    }
}
add_shortcode('dgfwimgdir', 'dgfw_img_dir');


function service_video_shortcode() {

    $meta = get_post_meta_single( $post->ID );

    if ( isset($meta['service_video_shortcode'])  ) {

        return '<!-- [service-video] --> ' . $meta['service_video_shortcode'] . '<!-- /[service-video] --> ';

    }

    else return FALSE;

}
add_shortcode('service-video', 'service_video_shortcode');
add_shortcode('service_video', 'service_video_shortcode');



function service_video_list_shortcode() {

    $meta = get_post_meta_single( $post->ID );

    if ( isset($meta['service_video_list_shortcode'])  ) {

        return '<!-- [service-video-list] --> ' . $meta['service_video_list_shortcode'] . '<!-- /[service-video-list] --> ';

    }

    else return FALSE;

}
add_shortcode('service-video-list', 'service_video_list_shortcode');
add_shortcode('service_video_list', 'service_video_list_shortcode');
