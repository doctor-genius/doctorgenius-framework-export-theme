<?php
add_action('after_switch_theme', 'dgfw_activate');
    
function dgfw_activate() {
    //DGFW internals
    $initial_options = array( 
        'framework_version' => DGFWVERSION,
        'fonts_toggle' => '0',
        'heading_font' => 'Raleway',
        'button_font' => 'Montserrat',
        'body_font' => 'Source Sans Pro'
    );
    add_option('dg_options', $initial_options ); //This will never overwrite one which already exists
    
    //Handle menu creation
    $menu_name = 'Primary Navigation';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    
    if( !$menu_exists){
        wp_create_nav_menu($menu_name);

        $menu_obj = get_term_by( 'name', $menu_name, 'nav_menu' );
        $menu_id = $menu_obj->term_id;

        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $locations['side'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );        
    }
    
    // Handle WP settings
    update_option( 'permalink_structure', '/blog/%postname%/' );
    update_option( 'uploads_use_yearmonth_folders', FALSE );
    
    //Remove default/unnecessary posts
    $sample = get_page_by_title( 'Sample Page');
    $sample = $sample->ID;
    wp_delete_post( $sample, TRUE );
    
    flush_rewrite_rules( TRUE );

}
