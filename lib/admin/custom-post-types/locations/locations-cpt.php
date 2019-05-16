<?php
// @todo these CPTS will share a majority of code; consider making OOP with abstract classes? 

// Global
add_action( 'init', 'locations_register_post_type' );

/**
 * Register Locations Custom Post Type
 */
function locations_register_post_type() {
    $labels = array(
        'name'               => 'Locations',
        'singular_name'      => 'Location',
        'add_new'            => 'Add Location',
        'add_new_item'       => 'Add New Location',
        'edit_item'          => 'Edit Location',
        'new_item'           => 'New Location',
        'view_item'          => 'View Location',
        'search_items'       => 'Search Locations',
        'not_found'          => 'No Locations found',
        'not_found_in_trash' => 'No Locations in the trash',
    );

    $supports = array(
        'title',
        'revisions',
        'editor',
        /*		'thumbnail',
                'excerpt',*/
    );

    $args = array(
        'labels'            => $labels,
        'supports'          => $supports,
        'public'            => TRUE,
        'show_in_menu'      => FALSE,
        'show_in_nav_menus' => FALSE,
        'capability_type'   => 'post',
        'rewrite'           => array( 'slug' => 'locations', 'with_front' => FALSE ), // Permalinks format
        'menu_position'     => NULL,
        'menu_icon'         => 'dashicons-megaphone',
        'has_archive'       => TRUE,
        'show_in_rest'      => TRUE
    );

    /**
     * Hook 'locations_post_type_args'
     *
     * Filters the array of args creating the Locations Post Type
     */
    $args = apply_filters( 'locations_post_type_args', $args );

    // Actually register it
    register_post_type( 'locations', $args );

}
