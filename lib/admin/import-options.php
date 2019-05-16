<?php
add_action( 'import_end', 'dgfw_import_complete' );
function dgfw_import_complete() {
    error_log( 'Attempting DGFW post-import routine: ');

    //Handle Home Page
    $home_page = get_page_by_title( 'Home' );
    if ( $home_page ) {
        $home_page_id = $home_page->ID;

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home_page_id );
        error_log( '&nbsp;&nbsp;&nbsp;&nbsp;Home Page found at ID ' . $home_page_id . ' and has been set to show on WP front.' );
    }

    flush_rewrite_rules( TRUE );
    error_log( 'DGFW post-import routine completed.');
}
