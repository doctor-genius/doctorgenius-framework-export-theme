<?php
/**
 * Class DG_Materialize_Navwalker
 *
 * Revises the default WP nav markup to match Materialize's expected structure
 *
 *
 *
 */
class DG_Materialize_Navwalker extends Walker {
    //var $display_dropdown_title;

    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

    function start_lvl( &$output, $depth = 0, $args = array() ) {

        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'dropdown-content',
            'dropdown-main-nav',
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // Build HTML for output.
        $output .= "\n" . $indent . ' class="' . $class_names . '">' . "\n";

        // For submenu dropdowns:
        if ( $display_depth == 1 ) {
            $output .= '<div class="container"><div class="row"><div class="nav-flex-dropdown">';
        }

    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {

        // For submenu dropdowns:
        if ( $depth == 0 ) {
            $output .= '</div><!--/.nav-flex-dropdown--></div><!-- /.row --></div><!-- /.container -->';
        }

        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";

    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;

        // Code Indent Formatting
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

        //Nav item ID(s)
        $id_names = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $anchor_ids = empty( $item->linkid ) ? array() : (array) $item->linkid;
        $anchor_ids[] = 'menu-item-'. $item->ID . '-anchor';

        //Nav item classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $anchor_classes = empty( $item->linkclass ) ? array() : (array) $item->linkclass;

        // Add active class 
        if(in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }

        // Classes related to nav item's children 
        $children = get_posts(array(
            'post_type' => 'nav_menu_item',
            'nopaging' => true,
            'numberposts' => 1,
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $item->ID
        ));
        if (!empty($children)) {
            $classes[] = 'dropdown';
            $anchor_classes[] = 'dropdown-button';
        }

        // Depth-dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth % 2 ? 'menu-item-odd-depth' : 'menu-item-even-depth' ),
            'menu-item-depth-' . $depth
        );
        $classes = array_merge( $classes, $depth_classes );
        $anchor_classes = array_merge( $anchor_classes, $depth_classes );

        // Apply any hooked nav menu class filters on the nav item, and remove empty classes
        $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $anchor_class_names = implode( ' ', apply_filters( 'nav_menu_anchor_class', array_filter( $anchor_classes ), $item ) );
        $anchor_id_names = implode( ' ', apply_filters( 'nav_menu_anchor_id', array_filter( $anchor_ids ), $item) );

        //Form the item's li
        $output     .= $indent . '<li';
        $output     .= ! empty( $id_names )         ? ' id="'     . esc_attr( $id_names         ) .'"' : '';
        $output     .= ! empty( $class_names )      ? ' class="'  . esc_attr( $class_names      ) .'"' : '';
        $output     .= '>';

        //Form the li's inner anchor
        $attributes =  ! empty( $anchor_id_names )    ? ' id="' .     esc_attr( $anchor_id_names    )  .'"' : '';
        $attributes .= ! empty( $anchor_class_names ) ? ' class="'  . esc_attr( $anchor_class_names )  .'"' : '';
        $attributes .= ! empty( $item->attr_title )   ? ' title="'  . esc_attr( $item->attr_title   )  .'"' : '';
        $attributes .= ! empty( $item->target )       ? ' target="' . esc_attr( $item->target       )  .'"' : '';
        $attributes .= ! empty( $item->xfn )          ? ' rel="'    . esc_attr( $item->xfn          )  .'"' : '';
        $attributes .= ! empty( $item->url )          ? ' href="'   . esc_attr( $item->url          )  .'"' : '';
        $attributes .= ! empty( $children )           ? ' data-activates="dropdown-'. $item->ID        .'"' : '';
        $anchor_output .= '<a'. $attributes .'>';
        $anchor_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

        // Append dropdown icon to anchor text
        if( !empty( $children ) ) {
            $anchor_output .= '<i class="hide material-icons right">arrow_drop_down</i>';
        }

        $anchor_output .= '</a>';
        $anchor_output .= $args->after;

        if( !empty( $children ) ) {
            $anchor_output .= '<ul id="dropdown-'.$item->ID.'"';
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $anchor_output, $item, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }

}

class DG_Materialize_Sidenav_Navwalker extends Walker {
    //var $display_dropdown_title;

    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

    function start_lvl( &$output, $depth = 0, $args = array() ) {

        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'dropdown-content',
            'dropdown-main-nav',
            'dropdown-side-nav',
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // Build HTML for output.
        $output .= "\n" . $indent . ' class="' . $class_names . '">' . "\n";

        // For submenu dropdowns:
        if ( $display_depth == 1 ) {
            $output .= '<div class="container"><div class="row"><div class="nav-flex-dropdown">';
        }

    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {

        // For submenu dropdowns:
        if ( $depth == 0 ) {
            $output .= '</div><!--/.nav-flex-dropdown--></div><!-- /.row --></div><!-- /.container -->';
        }

        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";

    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;

        // Code Indent Formatting
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

        //Nav item ID(s)
        $id_names = apply_filters( 'nav_menu_item_id', 'side-menu-item-'. $item->ID, $item, $args );
        $anchor_ids = empty( $item->linkid ) ? array() : (array) $item->linkid;
        $anchor_ids[] = 'side-menu-item-'. $item->ID . '-anchor';

        //Nav item classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $anchor_classes = empty( $item->linkclass ) ? array() : (array) $item->linkclass;

        // Add active class
        if(in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }

        // Classes related to nav item's children
        $children = get_posts(array(
            'post_type' => 'nav_menu_item',
            'nopaging' => true,
            'numberposts' => 1,
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $item->ID
        ));
        if (!empty($children)) {
            $classes[] = 'dropdown';
            $anchor_classes = array('dropdown-button', 'side-nav-dropdown-button');
        }

        // Depth-dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth % 2 ? 'menu-item-odd-depth' : 'menu-item-even-depth' ),
            'menu-item-depth-' . $depth
        );
        $classes = array_merge( $classes, $depth_classes );
        $anchor_classes = array_merge( $anchor_classes, $depth_classes );

        // Apply any hooked nav menu class filters on the nav item, and remove empty classes
        $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $anchor_class_names = implode( ' ', apply_filters( 'nav_menu_anchor_class', array_filter( $anchor_classes ), $item ) );
        $anchor_id_names = implode( ' ', apply_filters( 'nav_menu_anchor_id', array_filter( $anchor_ids ), $item) );

        //Form the item's li
        $output     .= $indent . '<li';
        $output     .= ! empty( $id_names )         ? ' id="'     . esc_attr( $id_names         ) .'"' : '';
        $output     .= ! empty( $class_names )      ? ' class="'  . esc_attr( $class_names      ) .'"' : '';
        $output     .= '>';

        //Form the li's inner anchor
        $attributes =  ! empty( $anchor_id_names )    ? ' id="' .     esc_attr( $anchor_id_names    )  .'"' : '';
        $attributes .= ! empty( $anchor_class_names ) ? ' class="'  . esc_attr( $anchor_class_names )  .'"' : '';
        $attributes .= ! empty( $item->attr_title )   ? ' title="'  . esc_attr( $item->attr_title   )  .'"' : '';
        $attributes .= ! empty( $item->target )       ? ' target="' . esc_attr( $item->target       )  .'"' : '';
        $attributes .= ! empty( $item->xfn )          ? ' rel="'    . esc_attr( $item->xfn          )  .'"' : '';
        $attributes .= ! empty( $item->url )          ? ' href="'   . esc_attr( $item->url          )  .'"' : '';
        $attributes .= ! empty( $children )           ? ' data-activates="sidenav-dropdown-'. $item->ID        .'"' : '';
        $anchor_output .= '<a'. $attributes .'>';
        $anchor_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

        // Append dropdown icon to anchor text
        if( !empty( $children ) ) {
            $anchor_output .= '<i class="hide material-icons fa-sort-desc right"></i>';
        }

        $anchor_output .= '</a>';
        $anchor_output .= $args->after;

        if( !empty( $children ) ) {
            $anchor_output .= '<ul id="sidenav-dropdown-'.$item->ID.'"';
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $anchor_output, $item, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }

}

/*
 * -------------------- CSS ID & Classes - Back End  ----------------------
 * Expose CSS ID & Classes in Admin Nav editor & Front-end display
 * postmeta fields: _menu_item_linkid, _menu_item_linkclass
 */

/*
 * Saves new field to postmeta for navigation
 */
add_action('wp_update_nav_menu_item', 'dg_nav_update',10, 3);
function dg_nav_update($menu_id, $menu_item_db_id, $args ) {
    if ( is_array($_REQUEST['menu-item-linkid']) ) {
        $linkid_value = $_REQUEST['menu-item-linkid'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_linkid', $linkid_value );
    }
    if ( is_array($_REQUEST['menu-item-linkclass']) ) {
        $linkclass_value = $_REQUEST['menu-item-linkclass'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_linkclass', $linkclass_value );
    }
}

/*
 * Adds value of new field to $item object that will be passed to DG_Walker_Nav_Menu_Edit
 */
add_filter( 'wp_setup_nav_menu_item','dg_setup_custom_nav_fields' );
function dg_setup_custom_nav_fields($menu_item) {
    $menu_item->linkid = get_post_meta( $menu_item->ID, '_menu_item_linkid', true );
    $menu_item->linkclass = get_post_meta( $menu_item->ID, '_menu_item_linkclass', true );

    return $menu_item;
}

/**
 * Use our custom admin menu nav walker instead of core default
 */
add_filter( 'wp_edit_nav_menu_walker', 'DG_nav_edit_walker',10,2 );
function DG_nav_edit_walker($walker,$menu_id) {
    return 'DG_Walker_Nav_Menu_Edit';
}

/**
 * Extend Walker_Nav_Menu_Edit class in core
 *
 * Create HTML list of nav menu input items.
 *
 */
class DG_Walker_Nav_Menu_Edit extends Walker_Nav_Menu  {
    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {}

    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        ob_start();
        $item_id = esc_attr( $item->ID );
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = '';
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) )
                $original_title = false;
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            $original_title = $original_object->post_title;
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( __( '%s (Invalid)' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( __('%s (Pending)'), $item->title );
        }

        $title = empty( $item->label ) ? $title : $item->label;

        ?>
    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
        <dl class="menu-item-bar">
            <dt class="menu-item-handle">
                <span class="item-title"><?php echo esc_html( $title ); ?></span>
                <span class="item-controls">
                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                    <span class="item-order hide-if-js">
                        <a href="<?php
                        echo wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'move-up-menu-item',
                                    'menu-item' => $item_id,
                                ),
                                remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                            ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
                        |
                        <a href="<?php
                        echo wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'move-down-menu-item',
                                    'menu-item' => $item_id,
                                ),
                                remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                            ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
                    </span>
                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
                    echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                    ?>"><?php _e( 'Edit Menu Item' ); ?></a>
                </span>
            </dt>
        </dl>

        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
            <?php if( 'custom' == $item->type ) : ?>
                <p class="field-url description description-wide">
                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                        <?php _e( 'URL' ); ?><br />
                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                    </label>
                </p>
            <?php endif; ?>
            <p class="description description-thin">
                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                    <?php _e( 'Navigation Label' ); ?><br />
                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                </label>
            </p>
            <p class="description description-thin">
                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                    <?php _e( 'Title Attribute' ); ?><br />
                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                </label>
            </p>
            <p class="field-link-target description">
                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
                    <?php _e( 'Open link in a new window/tab' ); ?>
                </label>
            </p>
            <p class="field-xfn description description-wide">
                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                    <?php _e( 'Link Relationship (XFN)' ); ?><br />
                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                </label>
            </p>
            <p class="field-css-classes description description-wide">
                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                    <?php /* DG core edit: labelling */ ?>
                    <?php _e( 'CSS Classes on li (optional, wordpress default)' ); ?><br />
                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                </label>
            </p>
            <?php
            /*
             * Core modification: Custom DG linkid & linkclass attributes
             */
            ?>
            <p class="field-linkid description description-wide">
                <label for="edit-menu-item-linkid-<?php echo $item_id; ?>">
                    <?php _e( 'Link anchor <b>ID</b> (optional, DG custom)' ); ?><br />
                    <input type="text" id="edit-menu-item-linkid-<?php echo $item_id; ?>" class="widefat code edit-menu-item-linkid" name="menu-item-linkid[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->linkid ); ?>" />
                </label>
            </p>
            <p class="field-linkclass description description-wide">
                <label for="edit-menu-item-linkclass-<?php echo $item_id; ?>">
                    <?php _e( 'Link anchor <b>Classes</b> (optional, DG custom)' ); ?><br />
                    <input type="text" id="edit-menu-item-linkclass-<?php echo $item_id; ?>" class="widefat code edit-menu-item-linkclass" name="menu-item-linkclass[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->linkclass ); ?>" />
                </label>
            </p>
            <?php
            /*
             * end nav mods
             */
            ?>
            <p class="field-description description description-wide">
                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                    <?php _e( 'Description' ); ?><br />
                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
                </label>
            </p>
            <div class="menu-item-actions submitbox">
                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
                    <p class="link-to-original">
                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                    </p>
                <?php endif; ?>
                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'delete-menu-item',
                            'menu-item' => $item_id,
                        ),
                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                    ),
                    'delete-menu_item_' . $item_id
                ); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
                ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
            </div>

            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
        </div><!-- .menu-item-settings-->
        <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }
}

/*
 * -------------------- CSS ID & Classes - Front End  ----------------------
 */

/**
 * When displaying a link, format anything saved in our linkid postmeta field as the css ID (alongside any incoming ids from elsewhere)
 */
function add_linkid_to_nav_link( $atts, $item ) {

    $auto_id = $atts['id'];
    $manual_id = NULL;

    if ( $item->linkid ) {
        $manual_id = $item->linkid;

        if ( $auto_id ) { $atts['id'] = $auto_id . ' ' . $manual_id; }
        else { $atts['id'] = $manual_id; }

    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_linkid_to_nav_link', 11, 3 );

/**
 * When displaying a link, format anything saved in our linkclass postmeta field as the css class (alongside any incoming classes from elsewhere)
 */
function add_linkclass_to_nav_link( $atts, $item ) {

    $auto_class = $atts['class'];
    $manual_class = NULL;

    if ( $item->linkclass ) {
        $manual_class = $item->linkclass;

        if ( $auto_class ) { $atts['class'] = $auto_class . ' ' . $manual_class; }
        else { $atts['class'] = $manual_class; }

    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_linkclass_to_nav_link', 11, 3 );

/*
 * -------------------- END CSS ID & Classes ----------------------
 */
