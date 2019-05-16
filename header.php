<?php global $post;
$dg_body_classes = 'fw-v' . DGFWVERSION;
$fw_options = get_option( 'dg_options' );
$logo_url = isset( $fw_options['company_logo_url'] ) ? $fw_options['company_logo_url'] : FALSE;
if ( $logo_url ) {
    preg_match( '@wp-content([\\\/]*)uploads([\\\/]*)(.*\..*)@', $logo_url,$matches ); 
    $logo_markup = '<img class="attachment-logo size-logo" src="' . get_site_url() . '/wp-content/uploads/' . $matches[3] . '">';
} else { 
    $logo_markup = '<span>No Logo Found</span>';
}

if ( $fw_options['locations_override'] ) { $dg_body_classes .= ' multilocation'; }
$additional_markup = get_post_meta( $post->ID, 'dg_additional_markup', TRUE );
$head_markup = ! isset( $additional_markup['head_markup'] ) ? '' : $additional_markup['head_markup'];
$body_markup = ! isset( $additional_markup['body_markup'] ) ? '' : $additional_markup['body_markup'];
$contact_page = get_page_by_title('Contact');
$contact_page = get_permalink( $contact_page->ID );
$address_link = ! empty( $fw_options['driving_directions'] ) ? $fw_options['driving_directions'] : $contact_page;  
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="UTF-8">
	<?php if ( $head_markup ) { echo '<!-- Additional Header Markup -->' . $head_markup . '<!-- END Header Markup -->'; } ?>
    <!-- wp_head BEGIN --><?php wp_head(); ?><!-- wp_head END -->
</head>
<body <?php body_class( $dg_body_classes  ); ?> id="body" >
<?php if( !function_exists('is_plugin_active') ) { include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); } // ensure is_plugin_active() exists (not on frontend) ?>
<?php if ( is_plugin_active( 'gtm-web-amp/gtm-web-amp.php' ) ) { web_gtm_body( ); } ?>
<?php if ( $body_markup ) { echo '<!-- Additional Body Markup -->' . $body_markup . '<!-- END Additional Body Markup -->'; } ?>
<div class="navbar-fixed">
    <nav class="large">
        <div class="nav-wrapper bg-nav">
            <!-- Logo -->
            <div class="nav-logo"><a href="<?php bloginfo('url'); ?>"><?php echo $logo_markup; ?></a></div>
            <!-- Top Bar -->
            <div class="bg-nav-complement top-nav-wrapper">
                <ul class="top-nav hide-on-small-only">
                    
                    <?php if ( ! $fw_options['locations_override'] ) : ?>
                        <li class="address">
                            <a href="<?php echo $address_link; ?>" class="btn-transparent" target="_blank" rel="noopener nofollow" title="Directions to <?php echo $fw_options['company_name'] ?>">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;
                                <?php echo display_address( array( 'use_address_element' => FALSE, 'line_breaks' => FALSE ) ); ?>
                            </a>
                        </li>
                        <li class="phone btn-tertiary">
                            <?php echo display_phone(
                                array(
                                    'button_classes' => '',
                                    'pre_inner_markup' => '<i class="fa fa-phone bg-tertiary-complement" aria-hidden="true"></i>Call Us!&nbsp;',
                                    'post_inner_markup' => ''
                                )
                            );
                            ?>
                        </li>                        
                    <?php else : ?>
                        <li class="multilocation-pages-dropdown"><!-- Location pages dropdown -->
	                        <?php if ( is_user_logged_in() ) {
		                        echo "<a class='edit-link edit-link-icon floating m-l' href='" . get_admin_url() .  "/edit.php?post_type=locations' target='blank'><i class='material-icons'>edit</i></a >";
	                        }  ?>        
                            
                            <!-- Dropdown Trigger -->
                            <a class="multi-location-nav btn-transparent" href="#" data-activates="multi-location-nav-dropdown">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>Locations
                            </a>
                            <!-- Dropdown Structure -->
                            <ul id="multi-location-nav-dropdown" class="dropdown-content bg-tertiary z-depth-1">
                                
                                <li><a href="<?php echo get_site_url(); ?>/locations/">All Locations</a></li>
                            
                                <?php
                                $args = array (
                                    'post_type'  => 'locations',
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                );
                                
                                $locations_query = new WP_Query( $args );
                                if ( $locations_query->have_posts() ) : while ( $locations_query->have_posts() ) :
                                    $locations_query->the_post();
                                    ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; else : ?>
                                    <p>No locations found!</p>
                                <?php endif; ?>
	                            <?php rewind_posts(); // We'll use the same query again for phone numbers to reduce server load ?>
                            </ul>
                        </li>
                        <li class="multilocation-phones-dropdown">  
                            <!-- Dropdown Trigger -->
                            <a class="multi-phone btn-tertiary" href="#" data-activates="multi-location-phone-dropdown" style=""><i class="fa fa-phone bg-tertiary-complement" aria-hidden="true"></i>Call Us Today</a>
                            
                            <!-- Dropdown Structure -->
                            <ul id="multi-location-phone-dropdown" class="dropdown-content bg-tertiary z-depth-1">
                                <?php 
                                if ( $locations_query->have_posts() ) : while ( $locations_query->have_posts() ) :
                                    $locations_query->the_post();
	                                $locations_meta = get_post_meta( $post->ID, 'dg_postmeta', true );
                                    ?>
                                    <li>
                                        <a href="tel:+1<?php echo $locations_meta['phone']; ?>">
			                                <?php the_title(); ?><br>
                                            <span class="client-phone"><?php echo $locations_meta['formatted_phone'] ?></span>
                                        </a>
                                    </li>
	                                <?php rewind_posts(); // We'll use the same query again for phone numbers to reduce server load ?>
                                <?php endwhile; else: ?>
                                    <p>No locations found!</p>
                                <?php endif; ?>                                
                            </ul>
                        </li>               
                    <?php endif; ?>
                    
                </ul>
            </div>
            <!-- Mobile Nav Button -->
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>

            <!-- Main Navigation -->
            <div class="main-nav-wrapper hide-on-med-and-down">
	            <?php
	            if ( has_nav_menu('primary') ) {
		            wp_nav_menu( array(
                        'depth'          => '0',
			            'menu'           => 'primary',
			            'theme_location' => 'primary',
			            'menu_class'     => 'main-nav',
			            'walker'         => new DG_Materialize_Navwalker()
		            ) );
	            }
	            ?>
            </div><!-- /.main-nav-wrapper -->

            <!-- Slide-out Side Navigation  -->
            <div class="side-nav-wrapper">
	            <?php
	            if ( has_nav_menu('primary') ) {
		            wp_nav_menu( array(
                        'depth'             => '1',
			            'menu'              => 'primary',
			            'theme_location'    => 'side',
			            'menu_class'        => 'side-nav bg-tertiary',
			            'menu_id'           => 'mobile-demo',
			            'container_class'   => 'menu-side-nav'
		            ) );
	            }
	            ?>
            </div><!-- /.side-nav-wrapper -->
            
        </div><!-- /.nav-wrapper -->
    </nav>
</div>

<!-- /header.php -->
