<?php
/**
 * Enable relative URLs across WP admin
 *
 * WordPress likes to use absolute URLs on everything - let's clean that up.
 * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
 * 
 * Bug in WP use of delimiters fixed
 */
function dg_relative_url($input) {
	
	preg_match('|https?://([^/]+)(/.*)|i', $input, $matches);

	if ( !isset($matches[1] ) || !isset( $matches[2]) ) {
		return $input;
	} 
	elseif ( ($matches[1] === $_SERVER['SERVER_NAME'] ) || $matches[1] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']) {
	    $input = preg_replace( '/^(https?:)?\/\/[^\/]+(\/?.*)/i', '$2', $input );
	    return $input;
	} 
	else {
		return $input;
	}
}

function dg_enable_relative_urls() {
	return !(is_admin() || in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) && current_theme_supports('dg-relative-urls-from-admin');
}

if ( dg_enable_relative_urls() ) {

	$dg_rel_filters = array(
	    'walker_nav_menu_start_el',
		'bloginfo_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'roots_wp_nav_menu_item',
		'the_content_more_link',
		'the_tags',
		'get_pagenum_link',
		'get_comment_link',
		'month_link',
		'day_link',
		'year_link',
		'tag_link',
		'the_author_posts_link',
		'script_loader_src',
		'style_loader_src'
	);

	add_filters($dg_rel_filters, 'dg_relative_url');
}


if ( current_theme_supports('make-relative-urls-in-content-absolute') ) {

    $dg_abs_filters = array(
        'the_content'
    );

    add_filters($dg_abs_filters, 'dg_output_relative_urls_as_absolute_urls');
}

/**
 * Replace relative URLs with absolute URLs  
  */
function dg_output_relative_urls_as_absolute_urls($input) {
    $absolute_replacement = site_url() . '$1';
    $relative_pattern = '/[\'"](?!https?:\/\/)(\/.*?\/?)[\'"]/i';
    return preg_replace( $relative_pattern, $absolute_replacement, $input );
}
