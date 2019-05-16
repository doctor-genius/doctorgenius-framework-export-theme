<?php
/* 
Template Name: All Posts [Blog Archive] 
*/
?>
<?php get_header(); ?>

<section class="inner-topbar bg-primary"></section>

<section class="inner-block bg-body">
    <div class="container">
        <div class="row">

            <div class="col s12 m12 l12 intro-col bg-body">
                <h1 class="title center-align">Recent Posts</h1>
                <div class="divider divider-dotted"></div>
                <div class="entry-content text-center">
                    <?php
                    $this_page = get_queried_object();
                    echo apply_filters( 'the_content', $this_page->post_content );
                    ?>
                </div>
            </div>
            <?php if ( is_paged() ) : ?>
            <div class="col s12 m12 l8 recent-blogs-content">
                <div class="row">
                    <div class="col s12">
                        <div class="blogs-pagination">
                            <div class="next-posts-link subtle-link"><?php next_posts_link( '« Older Posts', '' ); ?></div>
                            <div class="previous-posts-link subtle-link"><?php previous_posts_link( 'Newer Posts »'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col s12 m12 l8 recent-blogs-content">

                <div class="row">

                    <!-- Recent Posts Loop -->
                    <?php
                    $posts_query_args = array( 'post_type' => 'post' );
                    $posts_query =  new WP_Query( $posts_query_args );
                    if ( $posts_query->have_posts() ) : while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        <div class="col s12 m6 l4 recent-blog">
                            <a href="<?php the_permalink(); ?>" class="no-underline" title="Visit Our <?php the_title(); ?> blog post">
                                <div class="post-box-wrap hoverable bg-body-complement matchHeight">
                                    <?php echo dg_print_featured_or_first_inline_image( array( 'image_classes' => '', 'image-size' => 'recent-post-index', 'post' => $post, 'alttext' => 'Visit Our ' . get_the_title() . ' blog post' )  ); ?>
                                    <div class="post-text-box matchInnerHeight">
                                        
                                        <!--<p><?php echo get_the_time( 'F j, Y' ); ?></p>-->
                                        <h3 class="text-contrast"><?php the_title(); ?></h3>
                                        <div><?php echo lss_get_excerpt_by_id( $post->ID ); ?></div>
                                        
                                    </div>
                                </div>
                            </a>
                            
                        </div>
                    <?php endwhile; else : ?>
                        <p>Sorry, no recent posts found in database!</p>
                    <?php endif; ?>
                    <!-- End recent posts loop -->

                    <div class="row">
                        <div class="col s12">
                            <div class="blogs-pagination">
                                <div class="next-posts-link subtle-link"><?php next_posts_link( '« Older Posts', '' ); ?></div>
                                <div class="previous-posts-link subtle-link"><?php previous_posts_link( 'Newer Posts »'); ?></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col s12 m12 l4 contact-form-sidebar">
                <div class="content-block">
                    <div class="form-wrapper-default bg-form z-depth-1">
                        <?php echo do_shortcode('[contact-form main]'); ?>
                    </div>
                </div>
            </div>

        </div>
</section>

<?php get_footer(); ?>
