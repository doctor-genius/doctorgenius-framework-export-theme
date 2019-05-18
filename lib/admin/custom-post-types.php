<?php

//Custom Post Types brainstorm:
// Locations
// Staff
// Coupons
// Patient Forms
// Testimonials

/* Load all our theme's custom post types */
$dg_cpt_includes = array(
    get_template_directory() . '/lib/admin/custom-post-types/locations/locations-cpt.php',
);

foreach( $dg_cpt_includes as $file){
    require_once $file;
}
unset($file, $filepath);
