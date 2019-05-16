<?php

//@TODO this is a direct copy of the outdated recent blogs shortcode plugin. It needs to be overhauled completely

//don't allow the plugin to be accessed from the browser
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Include some functions needed for processing images on posts without 'featured images'

if ( ! function_exists( 'lss_first_image' ) ){
    function lss_first_image( $post = '' ) {
        if ( $post == '' ) global $post;

        $img = '';
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if ( isset($matches[1][0]) ) $img = $matches[1][0];

        return trim($img);
    }
}

/* this function gets thumbnail from Post Thumbnail or First post image */
if ( ! function_exists( 'lss_get_thumbnail_url' ) ){
    function lss_get_thumbnail_url($width=150, $height=150, $class='', $alttext='', $titletext='', $fullpath=false, $post='')
    {
        if ( $post == '' ) global $post;
        global $shortname;

        $thumb_array['thumb'] = '';

        if ( has_post_thumbnail( $post->ID ) ) {
            $lss_fullpath =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            $thumb_array['fullpath'] =  $lss_fullpath[0];
            $thumb_array['thumb'] = $thumb_array['fullpath'];
        }

        if ($thumb_array['thumb'] == '') {
            $thumb_array['thumb'] = esc_attr( get_post_meta($post->ID, 'Thumbnail', $single = true) );

            if (($thumb_array['thumb'] == '') ) {
                $thumb_array['thumb'] = esc_attr( lss_first_image( $post ) );
                if ( $fullpath ) $thumb_array['fullpath'] = $thumb_array['thumb'];
            }

        }

        if ( ! $thumb_array['thumb'] ) { return FALSE; }
        return $thumb_array['thumb'];
    }
}

/* this function prints thumbnail from Post Thumbnail or First post image */
if ( ! function_exists( 'lss_print_thumbnail' ) ){
    function lss_print_thumbnail($class = '', $width = 100, $height = 100, $thumbnail = '', $alttext = '', $echoout = true, $post='') {
        global $shortname;
        if ( $post == '' ) global $post;

        $output = '';
        $thumbnail_orig = $thumbnail;
        $thumb_url = lss_get_thumbnail_url( '', '', '', '', '', '', $post );

        if ( ! $thumb_url ) { return FALSE; }

        $output = '<img src="' . esc_url( $thumb_url ) . '"';

        if ($class <> '') $output .= " class='" . esc_attr( $class ) . "' ";

        $alttext = dg_prepare_alt_text( $post );

        //$output .= " alt='z" . esc_attr( strip_tags( $alttext ) ) . "' />";
        $output .= " alt='" . esc_attr( strip_tags(  $alttext ) ) . "' />";

        if ($echoout) echo $output;
        else return $output;
    }
}

/* this function prepares a logical alt text based on filename or post title */
if ( ! function_exists( 'dg_prepare_alt_text' ) ) {
    function dg_prepare_alt_text( $post ) {
        return $output = trim( ucwords( preg_replace( '/[\.|\-|\_|\d]/', ' ', get_the_title( $post )  ) ) );

    }
}




/* this function prints thumbnail from Post Thumbnail or First post image as a div with an inline background image */
if ( ! function_exists( 'lss_print_image_bg_thumbnail' ) ) {
    function lss_print_image_bg_thumbnail($class = '', $width = 100, $height = 100, $thumbnail = '', $alttext = '', $echoout = true, $post='') {
        global $shortname;
        if ( $post == '' ) global $post;

        $output = '';
        $thumbnail_orig = $thumbnail;
        $thumb_url = lss_get_thumbnail_url( '', '', '', '', '', '', $post );

        $output .= "<div class='image-wrapper " . esc_attr( $class ) . "'>";
        if ( $thumb_url ) {
            $output .= '<div class="image-container" style="background-image: url(' . esc_url( $thumb_url ) . ');" ></div>';
        }
        else {
            $output .= '<div class="image-container" style="background: white" ></div>';
        }





        // For some reason, we don't want to output alt tags on these posts
        // $output .= " alt='" . esc_attr( strip_tags( $alttext ) ) . "'></div>";

        $output .= "</div>";

        if ($echoout) echo $output;
        else return $output;
    }
}



/* Recent Blogs Shortcode
==============================================================================
    Usage: [recent-blogs]

    OPTIONAL arguments format:
    title="String"                          (Default: "Recent Posts")
    count=int  OR count="int"               (Default: 3)
    excerpt=true/false                      (Default: true)
    category="Comma,Separated,Categories"   (Default: no category)
    wrapper_class = 'css-class'             (Default: 'recent-blogs-wrapper')
    layout="vertical/horizontal"            (Default: 'vertical')
    bg_color = css color class Name         (Default: get_option('accent_banners_bg_colors_picker) / Fallback: 'fresh-blue')

    [recent-blogs count=2 title="Custom Title" excerpt=false category="implants,dentures"]
    ** note: if there aren't enough blogs in (category) to fill up to the (count) value, it will be supplemented with non-category posts up to total (count) **
==============================================================================*/

add_shortcode( 'recent-blogs', 'cp_sidebar_recent_blogs_shortcode');

function cp_sidebar_recent_blogs_shortcode( $atts, $content = null ) {

    // shortcode_atts will only allow us to supply 1 default; for the horizontal layout submitted with no title, use a different default
    $title_default = '';
    if ( isset( $atts['title'] )  && $atts['layout'] == 'horizontal' ) {
        $title_default = 'Related Posts';
    }
    else {
        $title_default = 'Recent Posts';
    }

    $a = shortcode_atts( array(
        'title' => $title_default,
        'count' => 1,
        'excerpt' => TRUE,
        'category' => false,
        'wrapper_class' => 'recent-blogs-wrapper',
        'layout' => 'vertical',
    ), $atts );

    // Shortcode won't allow direct entry of a boolean type, so we have to typecast it based on a string
    if ( $a['excerpt'] === 'false' || $a['excerpt'] === 'FALSE' ) $a['excerpt'] = false;

    //Form a SQL query for the argument categories, if any
    $query = lss_form_recent_blogs_query( $a['category'] );

    // Query the database with our built-up request
    global $wpdb;
    $results = $wpdb->get_results( $query );
    $results_count = count( $results );

    //return $results_count;


    if ( !empty( $results ) && $results_count > 0 ) {
        // Ideally these next 2 mods would be handled with a LIMIT sql directive in combo with DISTINCT id, but DISTINCT removes the first dupe and we need it in place. @todo

        // Filter out any duplicate posts, and any posts identical to the currently queried post

        $results = lss_filter_unique_post_array( $results );

        //Trim results to $a['count']
        $results = array_slice( $results, 0, $a['count'] );
        if ( $results && $results > 0 ) {

            //begin output of the recent posts sidebar area
            ob_start();
            //printf( '$results: %s |  $results_count: ', print_r( $results ), $results_count );
            switch ( $a['layout'] ) {

                case 'vertical' :
                    ?>
                    <div class="<?php echo $a['wrapper_class']; ?> <?php echo $a['layout']; ?>">
                        <h3 class="text-left text-contrast"><?php echo $a['title']; ?></h3>
                        <?php
                        foreach ( $results as $post ) {
                            lss_output_recent_post( $post->ID, $a['excerpt'], 'vertical' );
                        }
                        ?>
                    </div>
                    <?php
                    break;

                case 'horizontal' :
                    ?>
                    <div class="divider divider-thin"></div>

                    <div class="<?php echo $a['wrapper_class']; ?> <?php echo $a['layout']; ?>">
                        <h2 class="text-center"><?php echo $a['title']; ?></h2>

                        <div class="flexed">
                            <?php
                            foreach ( $results as $post ) {
                                lss_output_recent_post( $post->ID, $a['excerpt'], 'horizontal', $a['bg_color'] );
                            }
                            ?>
                        </div><!-- /flexed -->
                    </div>
                    <?php
                    break;

            }

            //output the buffered display shortcode output
            return ob_get_clean();
        }
        else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

if ( ! function_exists( 'lss_form_recent_blogs_query' ) ) {
    function lss_form_recent_blogs_query( $category_list ) {
        global $wpdb;

        // Create an array of category ID's requested by the shortcode.
        // @todo optimize performance; joins necessary?
        $categories_queried = explode( ',', $category_list );

        // @todo Remove non-existant categories

        $categories_queried_by_id = array();

        foreach ( $categories_queried as $category ) {
            if ( term_exists($category) ) {
                $cat_id = get_term_by( 'slug', $category, 'category' )->term_id;
                $categories_queried_by_id[] = $cat_id;
            }
            /* Deprecating error message:
            else {

                global $wp;
                $current_url = home_url(add_query_arg(array(),$wp->request));
                error_log('[Recent Blogs Error]: category "' . $category .'" does not exist.  Ignoring this term. Referenced on ' . $current_url, 0, 3 );
            }
            */
        }

        $query = "
            SELECT * FROM $wpdb->posts
            LEFT JOIN $wpdb->term_relationships ON
                ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
            LEFT JOIN $wpdb->term_taxonomy ON
                ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
            WHERE $wpdb->posts.post_status = 'publish'";

        $query .= " AND $wpdb->term_taxonomy.taxonomy = 'category'";

        $query .= " ORDER BY";

        if ( $categories_queried_by_id && count( $categories_queried_by_id ) > 0 ) {
            $query .= " (CASE";

            // Append the custom ORDERBY parameters based off the category ids so the query returns the posts in order of category
            for ( $i = 0; $i < count( $categories_queried ); $i++ ) {

                if ( $categories_queried_by_id[$i] ) {
                    $query .= " WHEN $wpdb->term_taxonomy.term_id = '" . $categories_queried_by_id[$i] . "' THEN " . ($i + 1);
                }
                if ( $i == count( $categories_queried ) - 1 ) {
                    $query .= " ELSE " . ( $i + 2 );
                }

            }

            $query .= " END), ";
        }
        $query .= " wp_posts.post_date DESC";
        return $query;
    }
}

// Filter out duplicate post IDs from results, as well as the queried page's post ID
if ( ! function_exists( 'lss_filter_unique_post_array' ) ) {
    function lss_filter_unique_post_array( $posts_array ) {

        $main_post = get_queried_object();
        $main_post_id = $main_post->ID;
        //printf( '$main_post_id: %s', $main_post_id  );

        $valid_ids = array();
        foreach ($posts_array as $key => $post ) {
            $id = $post->ID;
            //printf( '$post_id: %s', $id  );
            if ( get_post_type( $post->ID  ) != 'post' ) { unset( $posts_array[$key] ); }
            if ( in_array( $id, $valid_ids ) || ( $main_post_id == $id ) ) { unset( $posts_array[$key] ); }
            else { $valid_ids[] = $id; }
        }
        //printf( '$posts_array: %s', print_r( $posts_array, TRUE )  );
        if ( count( $posts_array ) > 0 ) {
            return $posts_array;
        }
        else {
            return FALSE;
        }
    }
}

// the_excerpt is deprecated outside The Loop, this function processes the post content as if it were in the loop
if ( ! function_exists( 'lss_get_excerpt_by_id' ) ) {
    function lss_get_excerpt_by_id( $post_id, $excerpt_length = '55' ){
        $the_post = get_post($post_id); //Gets post ID
        $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

        // Strip out new lines
        $the_excerpt = preg_replace( "/\r|\n/", "", $the_excerpt );

        // Strip shortcodes, specialized heading tags, other general html tags, and {} content left over from inline <script>
        $tags_to_strip = '/\<h[\d].*?\>.*?(\<\/h[\d]\>|$)|({.*})/m'; //Match any sequence beginning with <h# and ending with </h# or end of line (for sequences which don't capture the final closing h tag)
        $the_excerpt = strip_tags( strip_shortcodes( preg_replace( $tags_to_strip, '', $the_excerpt ) ) );

        //And trim output to 55 words
        $the_excerpt = wp_trim_words( $the_excerpt, $excerpt_length );

        return '<p>' . $the_excerpt . '</p>';
    }
}

// Markup for each post in the sidebar
// @todo isolate all markup to an option setting. This is hard-coded for now.
if ( ! function_exists( 'lss_output_recent_post' ) ) {
    function lss_output_recent_post( $post_id, $excerpt = TRUE, $layout = 'vertical', $bg_color = '' ) {
        if ( $layout == 'vertical' ) {
            echo '<div class="divider divider-thin"></div>';
        }
        else {
            $bg_color = 'bg-body-complement';
        }

        ?><div class="recent-posts matchHeight <?php if ( $bg_color != '' ) echo ' ' . $bg_color; ?>"><?php
        $sidebar_post = get_post( $post_id );

    if ( $layout == 'vertical' ) {
        ?><div class="recent-post-image">
        <a href="<?php echo get_the_permalink( $sidebar_post ); ?>" title="Visit our <?php echo get_the_title($sidebar_post); ?> blog post">
        <?php $thumb = lss_print_thumbnail( 'img-responsive', '', '', '', 'Visit Our ' . get_the_title( $sidebar_post ) . ' blog post', '', $sidebar_post );
        echo $thumb . '</a></div><!-- /recent-post-image -->';
    }
    elseif ( $layout == 'horizontal' ) {
        ?><a href="<?php echo get_the_permalink( $sidebar_post ); ?>" title="Visit our <?php echo get_the_title($sidebar_post); ?> blog post"><?php
        $thumb = lss_print_image_bg_thumbnail( '', '', '', '', '', '', $sidebar_post );
        echo $thumb;
        ?></a>
    <?php }
        ?><div class="recent-post-body">

        <!--<span class="time">
              <small>
                <?php //echo get_the_time( get_option('date_format'), $post_id ); ?>
              </small>
            </span><!-- /time -->

        <h4 class="blog-title text-contrast">
            <a href="<?php echo get_permalink( $post_id ); ?>" class="subtle-link" title="Visit Our <?php echo get_the_title($sidebar_post); ?> blog post">
                <?php echo ucwords( $sidebar_post->post_title ); ?>
            </a>
        </h4><!-- /title -->

        <?php if ( $excerpt ) : ?>
            <div class="excerpt small">
                <?php echo lss_get_excerpt_by_id( $post_id ); ?>
            </div><!-- /excerpt -->
        <?php endif; ?>

        </div><!-- /recent-post-body -->
        </div><!-- /recent-posts row -->


        <?php
    }
}




/* END Recent Blogs Shortcode
============================================================================== */
