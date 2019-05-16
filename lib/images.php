<?php 
/**
* Removes width and height attributes from image tags
*
* @param string $html
*
* @return string
*/
function remove_image_size_attributes( $html ) {
	return preg_replace( '/(width|height)="\d*"/', '', $html );
}

// Remove image size attributes from post thumbnails
add_filter( 'post_thumbnail_html', 'remove_image_size_attributes' );

// Remove image size attributes from images added to a WordPress post
add_filter( 'image_send_to_editor', 'remove_image_size_attributes' );



/* Image thumbnail helper functions: */
//@todo#14: see if there is a clearner way to deal with this now
//@todo#14 convert these argument lists into $args arrays


/* this function prints thumbnail from Post Thumbnail or First post image */
if ( ! function_exists( 'dg_print_thumbnail' ) ){
	function dg_print_featured_or_first_inline_image( $args ) {
		global $post;
		
		$defaults = array ( 
			'image_classes' => '',
			'alttext' => '',
			'post' => $post,
			'image_size' => 'full'
		);
		$args = wp_parse_args( $args, $defaults );
		
		$output = '';
		$image_url = '';
		$image_markup = NULL;

		if ( ! has_post_thumbnail( $post->ID ) ) {
			preg_match_all('/(<img.+src=[\'"]([^\'"]+)[\'"].*?>)/i', $post->post_content, $matches);
			if ( isset($matches[2][0]) ) { $image_url = $matches[2][0]; }
			
			$image_markup = "<div class=\"img-holder\" style=\"background-image: url('{$image_url}')\"></div>";
		

		} else {
			//$image_url = get_the_post_thumbnail_url( $args['post'], $args['image_size'] );
			$image_markup = get_the_post_thumbnail( $args['post'], $args['image_size'] );								
		}

		if ( ! $image_markup ) { 
			return FALSE; 
		} else { 
			return $image_markup;
		}

		/*
		$output = '<img src="' . esc_url( $image_url ) . '"';

		if ($class <> '') $output .= " class='" . esc_attr( $class ) . "' ";

		if ( ! $alttext || $alttext = ''  ) { $alttext = dg_prepare_alt_text( $image_url, $post ); }
		
		$output .= " alt='" . esc_attr( strip_tags( $alttext ) ) . "' />";

		if ($echoout) echo $output;
		else return $output;
		*/
	}
}


/* this function gets thumbnail from Post Thumbnail or First post image */
if ( ! function_exists( 'dg_get_thumbnail_url' ) ){
	function dg_get_thumbnail_url($width=150, $height=150, $class='', $alttext='', $titletext='', $fullpath=false, $post='', $image_size = 'full')
	{
		if ( $post == '' ) global $post;
		global $shortname;

		$thumb_array['thumb'] = '';

		if ( has_post_thumbnail( $post->ID ) ) {
			$lss_fullpath =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $image_size );
			$thumb_array['fullpath'] =  $lss_fullpath[0];
			$thumb_array['thumb'] = $thumb_array['fullpath'];
		}

		if ($thumb_array['thumb'] == '') {
			$thumb_array['thumb'] = esc_attr( get_post_meta($post->ID, 'Thumbnail', $single = true) );

			if (($thumb_array['thumb'] == '') ) {
				$thumb_array['thumb'] = esc_attr( dg_first_image( $post ) );
				if ( $fullpath ) $thumb_array['fullpath'] = $thumb_array['thumb'];
			}

		}

		if ( ! $thumb_array['thumb'] ) { return FALSE; }
		return $thumb_array['thumb'];
	}
}


if ( ! function_exists( 'dg_first_image' ) ){
	function dg_first_image( $post = '' ) {
		if ( $post == '' ) global $post;

		$img = '';
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
		if ( isset($matches[1][0]) ) $img = $matches[1][0];

		return trim($img);
	}
}

/* this function prints thumbnail from Post Thumbnail or First post image as a div with an inline background image */
if ( ! function_exists( 'dg_print_image_bg_thumbnail' ) ) {
	function dg_print_image_bg_thumbnail($class = '', $width = 100, $height = 100, $thumbnail = '', $alttext = '', $echoout = true, $post='', $image_size = 'full') {
		global $shortname;
		if ( $post == '' ) global $post;

		$output = '';
		$thumbnail_orig = $thumbnail;
		$thumb_url = dg_get_thumbnail_url( '', '', '', '', '', '', $post, $image_size );

		if ( ! $thumb_url ) { return FALSE; }

		$output .= "<div class='image-wrapper " . esc_attr( $class ) . "'>";

		$output .= '<div class="image-container" style="background-image: url(' . esc_url( $thumb_url ) . ');" ></div>';


		// For some reason, we don't want to output alt tags on these posts
		// $output .= " alt='" . esc_attr( strip_tags( $alttext ) ) . "'></div>";

		$output .= "</div>";

		if ($echoout) echo $output;
		else return $output;
	}
}

