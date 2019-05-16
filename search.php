<?php
/* 
Template Name: Search Results Page
*/

?>
<?php get_header(); ?>

<section class="inner-topbar bg-primary"></section>

<section class="inner-block">
    <div class="container">
        <div class="row">

            <div class="col s12 m12 l12 intro-col bg-body">
                <h1 class="title center-align">Search Results</h1>
                <div class="divider divider-dotted"></div>
                <div class="entry-content text-center">
                    <?php
                    $this_page = get_queried_object();
                    echo apply_filters( 'the_content', $this_page->post_content );
                    ?>
                </div>
            </div>

            <div class="col s12 m12 l8 search-blogs-content">

                <div class="row">

                    <div class="search-container">
                        <section id="primary" class="content-area">

                            <div class="search-page-form" id="ss-search-page-form"><?php get_search_form(); ?></div>

                            <?php if ( have_posts() ) : ?>

                                <header class="page-header">
                                    <span class="search-page-title"><?php printf( 'Search Results for: %s', '<span>' . get_search_query() . '</span>' ); ?></span>
                                </header><!-- .page-header -->

                                <br><br>

                                <?php /* Start the Loop */ ?>
                                <?php while ( have_posts() ) : the_post(); ?>

                                    <div class="col s12 m6 l4 search-blog">
                                        <a href="<?php the_permalink(); ?>" class="no-underline" title="Visit Our <?php the_title(); ?> blog post">
                                            <div class="post-box-wrap hoverable bg-body-complement matchHeight">
                                                <?php echo dg_print_featured_or_first_inline_image( array( 'image_classes' => '', 'image-size' => 'search-post-index', 'post' => $post, 'alttext' => 'Visit Our ' . get_the_title() . ' blog post' )  ); ?>
                                                <div class="post-text-box matchInnerHeight">

                                                    <!--<p><?php //echo get_the_time( 'F j, Y' ); ?></p>-->
                                                    <h3 class="text-contrast"><?php the_title(); ?></h3>
                                                    <div style="word-wrap: break-word;"><?php echo lss_get_excerpt_by_id( $post->ID ); ?></div>

                                                </div>
                                            </div>
                                        </a>

                                    </div>


                                <?php endwhile; ?>



                            <?php else : ?>

                                <p>Sorry, no posts matching those search parameters could be found.</p>

                            <?php endif; ?>


                        </section><!-- #primary -->
                    </div>

                    <br>
                    <br>

                    <div class="row">
                        <div class="col s12">
                            <div class="blogs-pagination">
                                <div class="next-posts-link subtle-link"><?php next_posts_link( '« Older Posts', '' ); ?></div>
                                <div class="previous-posts-link subtle-link"><?php previous_posts_link( 'Newer Posts Â»'); ?></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col s12 m6 l4 contact-form-sidebar">
                <div class="content-block">
                    <div class="form-wrapper-default bg-form z-depth-1">
                        <?php echo do_shortcode('[contact-form main]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>
