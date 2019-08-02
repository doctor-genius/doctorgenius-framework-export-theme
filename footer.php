<?php
$fw_options = get_option( 'dg_options' );

// Process meta array offsets into variables for use in template
/* Logo is slightly different for export theme */
$logo_url = isset( $fw_options['company_logo_url'] ) ? $fw_options['company_logo_url'] : FALSE;
if ( $logo_url ) {
    $logo_url = preg_replace( '/-\d*?x\d*?(\..*?)$/', '$1', $logo_url );
    preg_match( '@wp-content([\\\/]*)uploads([\\\/]*)(.*\..*)@', $logo_url,$matches );
    $company_logo = '<img class="attachment-logo size-full" src="' . get_site_url() . '/wp-content/uploads/' . $matches[3] . '">';
} else {
    $company_logo = '<span>No Logo Found</span>';
}

$company_email = !isset( $fw_options['company_email'] ) ? '' : $fw_options['company_email'];
$company_fax = !isset( $fw_options['company_fax'] ) ? '' : $fw_options['company_fax'];
$company_name = !isset( $fw_options['company_name'] ) ? '' : $fw_options['company_name'];
$driving_directions = !isset( $fw_options['driving_directions'] ) ? '' : $fw_options['driving_directions'];
$contact_form_title = !isset( $fw_options['main_contact_form_heading'] ) ? 'Request An Appointment' : apply_filters( 'the_content', $fw_options['main_contact_form_heading'] );
$footer_contact_row_box_1_markup = ! isset( $fw_options['footer_contact_row_box_1_markup'] ) ? '' : apply_filters( 'the_content', $fw_options['footer_contact_row_box_1_markup'] );
$footer_contact_row_box_1_link = ! isset( $fw_options['footer_contact_row_box_1_link'] ) ? '' : $fw_options['footer_contact_row_box_1_link'];
$footer_contact_row_box_2_markup = ! isset( $fw_options['footer_contact_row_box_2_markup'] ) ? '' : apply_filters( 'the_content', $fw_options['footer_contact_row_box_2_markup'] );
$footer_contact_row_box_2_link = ! isset( $fw_options['footer_contact_row_box_2_link'] ) ? '' : $fw_options['footer_contact_row_box_2_link'];
$footer_contact_row_box_3_markup = ! isset( $fw_options['footer_contact_row_box_3_markup'] ) ? '' : apply_filters( 'the_content', $fw_options['footer_contact_row_box_3_markup'] );
$footer_contact_row_box_3_link = ! isset( $fw_options['footer_contact_row_box_3_link'] ) ? '' : $fw_options['footer_contact_row_box_3_link'];
$footer_info_column_about_heading = ! isset( $fw_options['footer_info_column_about_heading'] ) ? '<h5>About</h5>' : $fw_options['footer_info_column_about_heading'];
$footer_info_column_about_paragraph = ! isset( $fw_options['footer_info_column_about_paragraph'] ) ? '' : apply_filters( 'the_content', $fw_options['footer_info_column_about_paragraph'] );
$footer_info_column_address_heading = empty( $fw_options['footer_info_column_address_heading'] ) ? '<h5>Office</h5>' : $fw_options['footer_info_column_address_heading'];
$footer_blogs_heading = ! isset( $fw_options['footer_blogs_heading'] ) ? '' : $fw_options['footer_blogs_heading'];
$footer_blogs_shortcode = ! isset( $fw_options['footer_blogs_shortcode'] ) ? '' : $fw_options['footer_blogs_shortcode'];

//Handle multilocation design


// Footer Links
$terms_of_use_link = get_page_by_title( 'Terms of Use' );
if ( $terms_of_use_link ) { $terms_of_use_link = get_permalink( $terms_of_use_link->ID ); }
$privacy_policy_link = get_page_by_title( 'Privacy Policy' );
if ( $privacy_policy_link ) { $privacy_policy_link = get_permalink( $privacy_policy_link->ID ); }
$sitemap_link = get_page_by_title( 'Sitemap' );
if ( $sitemap_link ) { $sitemap_link = get_permalink( $sitemap_link->ID ); }
$contact_us_link = '';
if ( ! $fw_options['locations_override'] ) {
    $contact_us_link = get_page_by_title( 'Contact' );
    $contact_us_link = get_permalink( $contact_us_link->ID );
}
else {
    $contact_us_link = get_site_url() . '/locations/';
}

// Footer Contact Row Defaults
if ( ! $footer_contact_row_box_1_markup ) {
    if ( ! $fw_options['locations_override'] ) {
        $footer_contact_row_box_1_markup = display_address( array( 'line_breaks' => TRUE, 'pre_output_markup' => '<a href="' . $contact_us_link. '">', 'post_output_markup' => '</a>' ) );
    } else {
        $footer_contact_row_box_1_markup = 'Our Locations';
    }
}
if ( ! $footer_contact_row_box_1_link ) {

    if ( $driving_directions ) {
        $footer_contact_row_box_1_link = '<a class="btn btn-tertiary waves-effect" target="_blank" href="' . $driving_directions . '">Get Directions</a>';
    } else {
        $footer_contact_row_box_1_link = '<a class="btn btn-tertiary waves-effect" target="_blank" href="' . $contact_us_link . '">Get Directions</a>';
    }
}
if ( ! $footer_contact_row_box_2_markup ) {
    if ( ! $fw_options['locations_override'] ) {
        $footer_contact_row_box_2_markup =  display_phone( array( 'pre_inner_markup' => 'Book Today!<br>' ) );
    } else {
        $footer_contact_row_box_2_markup = 'Call Us Today';
    }
}
if ( ! $footer_contact_row_box_2_link ) {
    $footer_contact_row_box_2_link = '<a class="btn btn-tertiary waves-effect" href="#contact-form-modal">Request Appointment</a>';
}
if ( ! $footer_contact_row_box_3_markup ) {
    if ( ! $fw_options['locations_override'] ) {
        $footer_contact_row_box_3_markup = 'Comments or <br>Suggestions?</a>';
    } else {
        $footer_contact_row_box_3_markup = 'Comments/Suggestions';
    }
}
if ( ! $footer_contact_row_box_3_link ) {
    $footer_contact_row_box_3_link = '<a class="btn btn-tertiary waves-effect" href="' . $contact_us_link . '">Contact Us</a>';
}

//Handle AwDA
$responsive_sticky_footer_columns = 'col s3';
if ( $fw_options['awda_toggle']  ) { $responsive_sticky_footer_columns = 'col s2'; }

?>

<!-- footer.php -->
<section class="contact-cta bg-primary">
    <div class="container">
        <div class="row center-align">
            <div class="col s12 m12 l4">
                <div class="box-wrap-first">
                    <div class="subtle-link text-link"><?php echo $footer_contact_row_box_1_markup; ?></div>
                    <?php if ( ! $fw_options['locations_override'] ) : //Single location ?>
                        <?php echo $footer_contact_row_box_1_link; ?>
                    <?php else : //Multilocation ?>
                        <!-- Dropdown Trigger -->
                        <a class="footer-multi-location btn btn-tertiary" href="#" data-activates="footer-multi-location-nav-dropdown" style="">Click for Locations</a>

                        <!-- Dropdown Structure -->
                        <ul id="footer-multi-location-nav-dropdown" class="footer-content-dropdown bg-tertiary dropdown-content z-depth-4">
                            <li><a href="<?php echo $contact_us_link; ?>">All Locations</a></li>
                            <!-- Locations loop -->
                            <?php
                            $locations_query = FALSE;
                            if ( $fw_options['locations_override'] ) {
                                global $locations_query;
                                if ( ! $locations_query ) {

                                    $args = array(
                                        'post_type' => 'locations',
                                        'orderby'   => 'date',
                                        'order'     => 'DESC'
                                    );
                                    $locations_query = new WP_Query( $args );
                                }
                            }
                            if ( $locations_query->have_posts() ) : while ( $locations_query->have_posts() ) : ?>
                                <?php
                                $locations_query->the_post();
                                $locations_meta = get_post_meta_single( $post->ID );
                                ?>
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php endwhile; else: ?>
                                <p>No locations found!</p>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); // We'll use the same query again for phone numbers to reduce server load ?>
                            <!-- END locations loop -->
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col s12 m12 l4">
                <div class="box-wrap">
                    <div class="subtle-link text-link"><?php echo $footer_contact_row_box_2_markup; ?></div>
                    <?php if ( ! $fw_options['locations_override'] ) : //Single location ?>
                        <?php echo $footer_contact_row_box_2_link; ?>
                    <?php else : //Multilocation ?>
                        <!-- Dropdown Trigger -->
                        <a class="footer-multi-location btn btn-tertiary" href="#" data-activates="footer-multi-location-phone-dropdown">Click for Locations</a>

                        <!-- Dropdown Structure -->
                        <ul id="footer-multi-location-phone-dropdown" class="footer-content-dropdown bg-tertiary dropdown-content z-depth-4">
                            <!-- Locations loop -->
                            <?php
                            $locations_query = FALSE;
                            if ( $fw_options['locations_override'] ) {
                                global $locations_query;
                                if ( ! $locations_query ) {

                                    $args = array(
                                        'post_type' => 'locations',
                                        'orderby'   => 'date',
                                        'order'     => 'DESC'
                                    );
                                    $locations_query = new WP_Query( $args );
                                }
                            }
                            if ( $locations_query->have_posts() ) : while ( $locations_query->have_posts() ) : ?>
                                <?php
                                $locations_query->the_post();
                                $locations_meta = get_post_meta_single( $post->ID );
                                ?>
                                <li>
                                    <a href="tel:+1<?php echo $locations_meta['phone']; ?>">
                                        <?php the_title(); ?><br>
                                        <span class="client-phone"><?php echo $locations_meta['formatted_phone'] ?></span>
                                    </a>
                                </li>
                            <?php endwhile; else: ?>
                                <p>No locations found!</p>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); // We'll use the same query again for phone numbers to reduce server load ?>
                            <!-- END locations loop -->
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col s12 m12 l4">
                <div class="box-wrap-last">
                    <div class="subtle-link text-link"><?php echo $footer_contact_row_box_3_markup; ?></div>
                    <?php echo $footer_contact_row_box_3_link; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-body">
    <div class="container">
        <div class="row valign-wrapper">

            <div class="brand col s12 m12 l6">
                <div class="img-wrap">
                    <?php echo $company_logo; ?>
                </div>
                <div class="website center"><?php echo get_site_url(); ?></div>
            </div>

            <div class="info col s12 m12 l6">
                <div class="row">

                    <?php if ( ! $fw_options['locations_override'] ) : //Single location markup: ?>
                        <div class="about-column col s12 m12 l12">
                            <div class="about-heading"><?php echo $footer_info_column_about_heading; ?></div>
                            <div class="about-paragraph"><?php echo $footer_info_column_about_paragraph; ?></div>
                        </div>
                        <div class="divider-column col s12">
                            <div class="divider divider-solid"></div>
                        </div>
                        <div class="office-column col s12 m6 l6">
                            <div class="office-heading"><?php echo $footer_info_column_address_heading; ?></div>
                            <p><b><?php echo $fw_options['company_name']; ?></b></p>
                            <p class="address"><?php echo display_address(); ?></p>
                        </div>
                        <div class="contact-info-column  col s12 m6 l6">
                            <div class="contact-info-heading"><h5>Contact</h5></div>
                            <div class="numbers-wrap">
                                <p class="phone"><b>Phone: </b><span class="text-contrast"><?php echo display_phone(); ?></span></p>
                                <?php if ( $fw_options['company_fax'] ) : ?><p class="fax"><b>Fax: </b><span class="text-contrast"><?php echo display_phone( array( 'phone_number' => $company_fax, 'click_to_call' => FALSE)  ); ?></span></p><?php endif; ?>
                                <?php if ( $fw_options['company_email'] ) : ?><p class="email"><b>Email: </b><span class="text-contrast"><a href="mailto:<?php echo $company_email; ?>"><?php echo $company_email; ?></a></span></p><?php endif; ?>
                            </div>
                        </div>
                    <?php else : //Multilocation markup: ?>
                        <div class="locations-display">
                            <?php //@todo#58 this h5 could potentially be an editable field ?>
                            <h5>Our Locations</h5>
                            <?php if ( is_user_logged_in() ) {
                                echo "<a class='edit-link edit-link-icon floating t-r' href='" . get_admin_url() .  "/edit.php?post_type=locations' target='blank'><i class='fa fa-edit'></i></a >";
                            }  ?>
                            <!-- Multilocations loop -->
                            <?php
                            $locations_query = FALSE;
                            if ( $fw_options['locations_override'] ) {
                                global $locations_query;
                                if ( ! $locations_query ) {

                                    $args = array(
                                        'post_type' => 'locations',
                                        'orderby'   => 'date',
                                        'order'     => 'DESC'
                                    );
                                    $locations_query = new WP_Query( $args );
                                }
                            }
                            if ( $locations_query->have_posts() ) : while ( $locations_query->have_posts() ) :
                                $locations_query->the_post(); ?>
                                <?php $locations_meta = get_post_meta_single( $post->ID ); ?>
                                <div class="multi-location-wrapper">
                                    <?php if ( is_user_logged_in() ) {
                                        echo "<a class='edit-link edit-link-icon floating t-r' href='" . get_edit_post_link() . "' target='blank'><i class='material-icons'>edit</i></a >";
                                    }  ?>
                                    <p class="address">
                                        <?php echo $locations_meta['address_line_1'] . '<br>' . $locations_meta['city']. ' ,' . $locations_meta['state'] . ' ' . $locations_meta['zip_code'] ; ?></p>
                                    <p class="phone">
                                        <b class="text-contrast"><?php echo $locations_meta['ctc_formatted_phone']; ?></b>
                                    </p>
                                </div>
                                <div class="divider divider-thin"></div>
                            <?php endwhile; else : ?>
                                <p>No locations found!</p>
                            <?php endif; wp_reset_postdata(); ?>
                            <!-- /end multilocations loop -->
                        </div>

                    <?php endif; ?>

                </div><!-- /.info -->
            </div><!-- /.row -->

        </div>
    </div>
</footer>

<section id="SubFooter" class="bg-body-complement">
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="copyright">Copyright © <?php echo date('Y'); ?> All Rights Reserved <?php echo $company_name; ?>.</div>
                <ul>
                    <?php if ( $terms_of_use_link ) : ?><li><a href="<?php echo $terms_of_use_link; ?>" class="text-contrast">Terms of Use</a></li>&nbsp;<li>/</li><?php endif; ?>
                    <?php if ( $privacy_policy_link ) : ?><li><a href="<?php echo $privacy_policy_link; ?>" class="text-contrast">Privacy Policy</a></li>&nbsp;<li>/</li><?php endif; ?>
                    <?php if ( $sitemap_link ) : ?><li><a href="<?php echo $sitemap_link; ?>" class="text-contrast">Sitemap</a></li><?php endif; ?>
                </ul>
            </div>
            <div class="col s12 m6 l6">
                <div class="social-wrap">
                    <?php if ( $facebook = $fw_options['facebook'] ) : ?><a href="<?php echo $facebook; ?>" target="_blank" rel="noopener"><i class="fa fa-facebook fa-lg" title="Facebook"></i><span class="icon-text">Facebook</span></a><?php endif; ?>
                    <?php if ( $twitter = $fw_options['twitter'] ) : ?><a href="<?php echo $twitter; ?>" target="_blank" rel="noopener"><i class="fa fa-twitter fa-lg" title="Twitter"></i><span class="icon-text">Twitter</span></a><?php endif; ?>
                    <?php if ( $googleplus = $fw_options['googleplus'] ) : ?><a href="<?php echo $googleplus; ?>" target="_blank" rel="noopener"><i class="fa fa-google-plus fa-lg" title="Google+"></i><span class="icon-text">Google+</span></a><?php endif; ?>
                    <?php if ( $pinterest = $fw_options['pinterest'] ) : ?><a href="<?php echo $pinterest; ?>" target="_blank" rel="noopener"><i class="fa fa-pinterest fa-lg" title="Pinterest"></i><span class="icon-text">Pinterest</span></a><?php endif; ?>
                    <?php if ( $youtube = $fw_options['youtube'] ) : ?><a href="<?php echo $youtube; ?>" target="_blank" rel="noopener"><i class="fa fa-youtube fa-lg" title="Youtube"></i><span class="icon-text">YouTube</span></a><?php endif; ?>
                    <?php if ( $yelp = $fw_options['yelp'] ) : ?><a href="<?php echo $yelp; ?>" target="_blank" rel="noopener"><i class="fa fa-yelp fa-lg" title="Yelp"></i><span class="icon-text">Yelp</span></a><?php endif; ?>
                    <?php if ( $instagram = $fw_options['instagram'] ) : ?><a href="<?php echo $instagram; ?>" target="_blank" rel="noopener"><i class="fa fa-instagram fa-lg" title="Instagram"></i><span class="icon-text">Instagram</span></a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="sticky-footer valign-wrapper hide-on-small-only bg-nav">
    <div class="container">
        <div class="row">
            <div class="center-align">
                <p class="text">Book Your Appointment Today!</p>
                <!-- Modal Forms Trigger -->
                <a class="btn btn-tertiary waves-effect modal-trigger" href="#contact-form-modal"><i class="fa fa-calendar" aria-hidden="true"></i>Request Appointment</a>
            </div>
        </div>
    </div>
</div>

<div class="sticky-footer valign-wrapper hide-on-med-and-up bg-nav">
    <div class="container">
        <div class="row valign-wrapper">
            <div class="<?php echo $responsive_sticky_footer_columns; ?> center-align">
                <a href="/" class="btn-floating btn-medium waves-effect waves-light btn-tertiary">
                    <i class="fa fa-home"></i>
                    <span class="icon-text">Visit the Home Page</span>
                </a>
            </div>
            <div class="<?php echo $responsive_sticky_footer_columns; ?> center-align">
                <?php echo display_phone( array( 'custom_inner_markup' => '<i class="fa fa-phone"></i><span class="icon-text">Contact Us</span>', 'button_classes' => 'btn-floating btn-medium waves-effect waves-light btn-tertiary' ) ); ?>
            </div>
            <div class="<?php echo $responsive_sticky_footer_columns; ?> center-align">
                <a href="#contact-form-modal" class="btn-floating btn-medium waves-effect waves-light btn-tertiary modal-trigger">
                    <i class="fa fa-calendar"></i>
                    <span class="icon-text">Schedule an Appointment</span>
                </a>
            </div>
            <div class="<?php echo $responsive_sticky_footer_columns; ?> center-align">
                <a href="<?php echo $contact_us_link; ?>" class="btn-floating btn-medium waves-effect waves-light btn-tertiary modal-trigger" target="_blank">
                    <i class="fa fa-map-marker"></i>
                    <span class="icon-text">Get Driving Directions</span>
                </a>
            </div>
            <?php
            if ( $fw_options['awda_toggle'] ) {
                insert_awda_button_markup_mobile( );
            }
            if ( $fw_options['translator_toggle'] ) {
                insert_translator_selector_markup_mobile();
            }
            ?>
        </div>
    </div>
</div>

<!-- Modal Forms -->
<div id="contact-form-modal" class="modal">
    <div class="modal-content">
        <div class="content-block">
            <div class="form-wrapper bg-form z-depth-1">
                <div class="modal-close bg-tertiary waves-effect"><i class="fa fa-times" aria-hidden="true"></i></div>
                <?php echo do_shortcode('[contact-form main]'); ?>
            </div>
        </div>
    </div>
</div>

<?php
$additional_markup = get_post_meta( $post->ID, 'dg_additional_markup', TRUE );
$footer_markup = ! isset( $additional_markup['footer_markup'] ) ? '' : $additional_markup['footer_markup'];
if ( $footer_markup ) { echo '<!-- Additional Footer Markup -->' . $footer_markup . '<!-- END Additional Footer Markup -->'; }
?>
<!-- This is a temporary hacky hotfix -->
<script>
    jQuery('.page-id-9818').addClass('page-template-page-home-php');
    jQuery('.page-id-922').addClass('page-template-page-about-php');
    jQuery('.page-id-9820').addClass('page-template-page-patientinfo-php');
    jQuery('.page-id-9817').addClass('page-template-page-contact-php');
    jQuery('.page-id-9821').addClass('page-template-page-testimonials-php');
</script>

<!-- wp_footer() output -->
<?php wp_footer(); ?>
<!-- END wp_footer() output -->

</body>
</html>
<!--/footer.php -->
