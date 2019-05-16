<?php

add_filter( 'feed_links_show_comments_feed', '__return_false' );

if ( !function_exists( 'dg_remove_single_comments_feed' ) ){
	function dg_remove_single_comments_feed(){
		return;
	}
	add_filter( 'post_comments_feed_link', 'dg_remove_single_comments_feed' );
}

if( !function_exists( 'dg_comments_feed_404' ) ){
	function dg_comments_feed_404( $object ) {
		if ( $object->is_comment_feed ) {
			wp_die( 'Page not found.', '', array(
				'response'  => 404,
				'back_link' => true, 
			));
		}
	}
}
add_action( 'parse_query', 'dg_comments_feed_404' );


// Disable support for comments and trackbacks in post types
if ( !function_exists( 'dg_disable_comments_post_types_support' ) ){
	function dg_disable_comments_post_types_support() {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}
}
add_action('admin_init', 'dg_disable_comments_post_types_support');


// Close comments on the front-end
if ( !function_exists( 'dg_disable_comments_status' ) ) {
	function dg_disable_comments_status() {
		return FALSE;
	}
}
add_filter('comments_open', 'dg_disable_comments_status', 20, 2);
add_filter('pings_open', 'dg_disable_comments_status', 20, 2);

// Hide existing comments
if ( !function_exists( 'dg_disable_comments_hide_existing_comments' ) ) {
	function dg_disable_comments_hide_existing_comments( $comments ) {
		$comments = array();

		return $comments;
	}
}
add_filter('comments_array', 'dg_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
if ( !function_exists( 'dg_disable_comments_admin_menu' ) ) {
	function dg_disable_comments_admin_menu() {
		remove_menu_page( 'edit-comments.php' );
	}
}
add_action('admin_menu', 'dg_disable_comments_admin_menu');

// Redirect any user trying to access comments page
if ( !function_exists( 'dg_disable_comments_admin_menu_redirect' ) ) {
	function dg_disable_comments_admin_menu_redirect() {
		global $pagenow;
		if ( $pagenow === 'edit-comments.php' ) {
			wp_redirect( admin_url() );
			exit;
		}
	}
}
add_action('admin_init', 'dg_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
if ( !function_exists( 'dashboard_recent_comments' ) ) {
	function dg_disable_comments_dashboard() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}
}
add_action('admin_init', 'dg_disable_comments_dashboard');

// Remove comments links from admin bar
if ( !function_exists( 'dg_disable_comments_admin_bar' ) ) {
	function dg_disable_comments_admin_bar() {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}
}
add_action('init', 'dg_disable_comments_admin_bar');
