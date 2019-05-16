<?php 

/* Temp area for global client variables */
add_filter( 'no_texturize_tags', 'dg_no_texturzie_tags' );
function dg_no_texturzie_tags( $tags ) {
    $tags[] = 'blockquote';
    $tags[] = 'p';
    return $tags;
}
