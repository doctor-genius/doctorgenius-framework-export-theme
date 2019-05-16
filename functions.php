<?php

DEFINE( 'DGFWVERSION', '2.0.3-dev' );

add_action( 'after_setup_theme', 'doctorgenius_theme_setup' );
function doctorgenius_theme_setup() {

    /* WP Core features */
    if ( function_exists( 'add_theme_support' ) ) {
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
    }

    register_nav_menus( array( 'primary'    => 'Primary Navigation' ) );
    register_nav_menus( array( 'side'       => 'Slide-out Side Navigation' ) );

    /* Load all our theme library files */
    $dg_theme_includes = array(
        '/lib/awda-accessibility.php',                          // Coordinates functionality for the AwDA button, cookie, and styling
        '/lib/disable-wpautop.php',                             // Forces WP to not auto-format line breaks with <p> tags unnecessarily
        '/lib/easy-client-info.php',                            // Outputting client's info in particular formats  
        '/lib/helper-functions.php',                            // Basic reusable functions
        '/lib/images.php',                                      // Image sizes and thumbnail functions
        '/lib/nav.php',                                         // Alters the default WP nav to reflect the markup Materialize depends on  
        '/lib/no-comments.php',                                 // Disable Comments sitewide; admin menus, templates, feeds, links, registration, admin bar
        '/lib/no-emojis.php',                                   // Disable emoji styles, editor buttons, and DNS prefetch for assets
        '/lib/no-texturize-tags.php',                           // Filters out automatic WP tag texturization  
        '/lib/no-visual-editor.php',                            // Filters out automatic WP tag texturization  
        '/lib/recent-posts.php',                                // Coordinates the display of recent posts in sidebars (and shortcode)  
        '/lib/relative-urls.php',                               // Enable relative URLs
        '/lib/search.php',                                      // Use our custom search form, and create a [searchform] shortcode 
        '/lib/translator.php',                                  // Adds the necessary markup and JS to produce the Google Translator box  
        '/lib/admin/import-options.php',                        // Actions to take after in import
        '/lib/admin/theme-activation.php',                      // Activation routine, updates default db options 
        '/lib/admin/custom-post-types.php',                     // Locations CPT needed even in exported site 
    );



    foreach( $dg_theme_includes as $file){
        if( !$filepath = locate_template($file) ) {
            trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
        }

        require_once $filepath;
    }
    unset($file, $filepath);
}

// Global / front-end
add_action( 'wp_enqueue_scripts', 'dg_load_scripts' );
function dg_load_scripts() {
    if ( ! isset( $fw_options) ) {
        $fw_options = get_option( 'dg_options' );
    }

    //Load scripts for all templates:
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'materialize', get_template_directory_uri() . '/js/materialize.js', array( 'jquery' ), NULL, TRUE  );
    wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.js', array( 'jquery' ), NULL, TRUE  );
    wp_enqueue_script( 'jquery_move', get_template_directory_uri() . '/js/jquery.event.move.js', array( 'jquery' ), NULL, TRUE  );
    wp_enqueue_script( 'match_height', get_template_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ), NULL, TRUE  );
    wp_enqueue_script( 'dg_scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery', 'materialize' ), NULL, TRUE  );

    //Load stylesheets for all templates:
    wp_enqueue_style( 'materialize_stylesheet', get_template_directory_uri() . '/css/materialize-modified.css', NULL, NULL );
    wp_enqueue_style( 'dg_stylesheet', get_template_directory_uri() . '/css/layout.css', array( 'materialize_stylesheet' ), NULL );
    wp_enqueue_style( 'font_awesome_stylesheet', get_template_directory_uri() . '/css/font-awesome.css', array( 'dg_stylesheet' ), NULL );
    wp_enqueue_style( 'slick_default_stylesheet', get_template_directory_uri() . '/css/slick-default.css', array( 'dg_stylesheet' ), NULL );
    wp_enqueue_style( 'google_material_icons_stylesheet', get_template_directory_uri() . '/css/google-material-icons.css', array( 'dg_stylesheet' ), NULL );

    // Colors: Load the default color sheet unless a client-specific one has been created (dg-colors.css)
    if ( file_exists( get_stylesheet_directory() . '/css/dg-colors.css' ) ) {
        wp_enqueue_style( 'dg_colors', get_stylesheet_directory_uri() . '/css/dg-colors.css', array( 'dg_stylesheet' ) );
    } else {
        wp_enqueue_style( 'dg_colors_default', get_template_directory_uri() . '/css/dg-colors-Default.css', array( 'dg_stylesheet' ) );
    }

    // Enqueue all of the font files to be used on the site.
    wp_enqueue_style( 'font_source_sans_pro', get_template_directory_uri() . '/fonts/source-sans-pro.css', array( 'dg_stylesheet' ), NULL );
    wp_enqueue_style( 'font_roboto', get_template_directory_uri() . '/fonts/roboto.css', array( 'dg_stylesheet' ), NULL );
    wp_enqueue_style( 'font_raleway', get_template_directory_uri() . '/fonts/raleway.css', array( 'dg_stylesheet' ), NULL );
    wp_enqueue_style( 'font_montserrat', get_template_directory_uri() . '/fonts/montserrat.css', array( 'dg_stylesheet' ), NULL );

    /* Conditional Script and Style Loading based on FW options: */
    if ( $fw_options['awda_toggle'] ) {
        wp_enqueue_script( 'awda_accessibility', get_template_directory_uri().'/js/awda-accessibility.js', array( 'jquery' ), NULL, TRUE );
        wp_enqueue_style( 'awda_accessibility', get_template_directory_uri() . '/css/awda-accessibility.css', NULL, NULL );
    }

}


// Intercept requests for 'dg_options' and serve info from hardcoded file info instead
add_filter( 'option_dg_options', 'serve_static_dg_options' );
function serve_static_dg_options() {

    try {

        //Load our manually created/copied static framework options json file
        $filename = __DIR__ . '/client-options.json';

        if ( !file_exists($filename) ) {
            throw new Exception('File not found.');
        }

    } catch (Exception $e) {
        error_log(  'Error encountered locating client-options.json at intended location [' . $e->getMessage() . ']. Attempting parent directory.' );

        try {
            //Load our manually created/copied static framework options json file
            $filename = dirname( __DIR__  ) . '/client-options.json';


            if ( !file_exists($filename) ) {
                throw new Exception('File not found.');
            }

        } catch( Exception $f ) {
            error_log(  'Error encountered locating client-options.json at parent location of intended directory [' . $f->getMessage() . ']. Expanding search to /lib/.' );

            try {
                //Load our manually created/copied static framework options json file
                $filename = __DIR__ . '/lib/client-options.json';

                if ( !file_exists($filename) ) {
                    throw new Exception('File not found.');
                }

            } catch( Exception $g ) {
                error_log(  'Error encountered locating client-options.json at /lib/ [' . $g->getMessage() . ']. Expanding search to /lib/admin/.' );

                try {
                    //Load our manually created/copied static framework options json file
                    $filename = __DIR__ . '/lib/admin/client-options.json';

                    if ( !file_exists($filename) ) {
                        throw new Exception('File not found.');
                    }

                } catch( Exception $h ) {
                    error_log(  'Error encountered locating client-options.json at the parent root of intended location within a forward-slash route [' . $h->getMessage() . ']. ' );
                    error_log( 'Your file system appears to route resource requests through a filter which is preventing DoctorGenius Export Theme from locating your client settings.  Please contact your hosting provider for assistance.' );
                }
            }
        }

    }

    $file_options = file_get_contents( $filename );

    //error_log(  'client-options file search: ' . $filename );
    $unslashed_options = $file_options;
    $json_decoded_options = json_decode( $unslashed_options, TRUE );
    $dg_options = $json_decoded_options['wp_options']['dg_options'];

    //$json_errors = get_json_errors_table( );
    //error_log( $json_errors[ json_last_error() ] );

    return $dg_options;
}

function get_json_errors_table() {
    $constants   = get_defined_constants( TRUE );

    $output = array();
    foreach ( $constants["json"] as $name => $value ) {
        if ( ! strncmp( $name, "JSON_ERROR_", 11 ) ) {
            $output[ $value ] = $name;
        }
    }
    return $output;
}

add_filter( 'the_content', 'format_google_translate_script_link_correctly', 100 );
function format_google_translate_script_link_correctly( $content ) {
    $google_translate_script_link_pattern = '/http:\/\/localhost\/site-export-theme\/\/translate.google.com\/translate_a/s';
    $content = preg_replace( $google_translate_script_link_pattern, '//translate.google.com/translate_a', $content );

    return $content;
}

// Notify if CF7 is not activated
if ( ! function_exists( 'is_plugin_active' ) ) { include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
    add_action( 'admin_notices', 'suggest_cf7_plugin' );
}

function suggest_cf7_plugin() {
    $class = 'notice notice-error';
    $message = 'DoctorGenius Site Export Theme requires the Contact Form 7 plugin.  <a href="'.  admin_url( 'plugin-install.php?s=contact+form+7&tab=search&type=term' ) . '">Download and activate here</a>.';

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
}

