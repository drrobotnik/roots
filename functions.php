<?php
/**
 * Roots functions
 */

if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

require_once locate_template('/inc/util.php');            // Utility functions

add_theme_support('root-relative-urls');
add_theme_support('rewrite-urls');
add_theme_support('h5bp-htaccess');
add_theme_support('bootstrap-responsive');
add_theme_support('bootstrap-top-navbar');

// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = 940; }

define('POST_EXCERPT_LENGTH',       40);
define('WRAP_CLASSES',              'container');
define('CONTAINER_CLASSES',         'row');
define('MAIN_CLASSES',              'span8');
define('SIDEBAR_CLASSES',           'span4');
define('FULLWIDTH_CLASSES',         'span12');
define('GOOGLE_ANALYTICS_ID',       '');

// Set the post revisions to 5 unless previously set to avoid DB bloat
if (!defined('WP_POST_REVISIONS')) { define('WP_POST_REVISIONS', 5); }

define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next(explode('/themes/', get_template_directory())));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);


require_once locate_template('/inc/activation.php');      // Theme activation
require_once locate_template('/inc/template-tags.php');   // Template tags
require_once locate_template('/inc/cleanup.php');         // Cleanup
require_once locate_template('/inc/scripts.php');         // Scripts and stylesheets
require_once locate_template('/inc/htaccess.php');        // Rewrites for assets, H5BP .htaccess
require_once locate_template('/inc/hooks.php');           // Hooks
require_once locate_template('/inc/actions.php');         // Actions
require_once locate_template('/inc/widgets.php');         // Sidebars and widgets
require_once locate_template('/inc/custom.php');          // Custom functions

function roots_setup() {

  // Make theme available for translation
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'roots'),
  ));

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(150, 150, false);
  // add_image_size('category-thumb', 300, 9999); // 300px wide (and unlimited height)

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('css/editor-style.css');

} 

add_action('after_setup_theme', 'roots_setup');

// custom meta box for 'event' custom post type
/* include('/inc/events-meta.php');
function ep_eventposts_metaboxes() {
    add_meta_box( 'ept_event_date_start', 'Start Date and Time', 'ept_event_date', 'event', 'side', 'default', array( 'id' => '_start') );
    add_meta_box( 'ept_event_date_end', 'End Date and Time', 'ept_event_date', 'event', 'side', 'default', array('id'=>'_end') );
    add_meta_box( 'ept_event_location', 'Event Location', 'ept_event_location', 'event', 'side', 'default', array('id'=>'_end') );
}
add_action( 'admin_init', 'ep_eventposts_metaboxes' );
*/

if(!get_option('acf_repeater_ac')) update_option('acf_repeater_ac', "QJF7-L4IX-UCNP-RF2W");
if(!get_option('acf_options_ac')) update_option('acf_options_ac', "OPN8-FA4J-Y2LW-81LS");
if(!get_option('acf_flexible_content_ac')) update_option('acf_flexible_content_ac', "FC9O-H6VN-E4CL-LT33");
if(!get_option('rg_gforms_key')) update_option('rg_gforms_key', md5("75dd50af576dd43d192c981a2a09120d"));

function jt_oembed_get( $url, $args = '' ) {
require_once( ABSPATH . WPINC . '/class-oembed.php' );
$oembed = _wp_oembed_get_object();
$provider = $oembed->discover( $url );
return $oembed->fetch($provider, $url, $args );
}

add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
  global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
  if($is_lynx) $classes[] = 'lynx';
  elseif($is_gecko) $classes[] = 'gecko';
  elseif($is_opera) $classes[] = 'opera';
  elseif($is_NS4) $classes[] = 'ns4';
  elseif($is_safari) $classes[] = 'safari';
  elseif($is_chrome) $classes[] = 'chrome';
  elseif($is_IE) $classes[] = 'ie';
  else $classes[] = 'unknown';
  if($is_iphone) $classes[] = 'iphone';
  return $classes;
}



function remove_dashboard_widgets() {
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

function jt_rss_output(){
    echo '<div class="rss-widget">';
     
       wp_widget_rss_output(array(
            'url' => 'http://www.jacobtyler.com/blog/feed/',  //put your feed URL here
            'title' => 'Latest News from Jacob Tyler', // Your feed title
            'items' => 2, //how many posts to show
            'show_summary' => 1, // 0 = false and 1 = true 
            'show_author' => 0,
            'show_date' => 1
       ));
       
       echo "</div>";
}

// Hook into wp_dashboard_setup and add our widget
add_action('wp_dashboard_setup', 'jt_rss_widget');
  
// Create the function that adds the widget
function jt_rss_widget(){
  // Add our RSS widget
  wp_add_dashboard_widget( 'jt-rss', 'Latest News from Jacob Tyler', 'jt_rss_output');
  
  // Globalize the metaboxes array, this holds all the widgets for wp-admin

  global $wp_meta_boxes;

  // Get the regular dashboard widgets array 
  // (which has our new widget already but at the end)

  $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

  // Backup and delete our new dashbaord widget from the end of the array

  $example_widget_backup = array('jt-rss' => $normal_dashboard['jt-rss']);
  unset($normal_dashboard['jt-rss']);

  // Merge the two arrays together so our widget is at the beginning

  $sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
  unset($normal_dashboard['jt-rss']);
  $side_dashboard = array_merge($example_widget_backup, $normal_dashboard);
  unset($side_dashboard['dashboard_right_now']);
  // Save the sorted array back into the original metaboxes 

  $wp_meta_boxes['dashboard']['normal']['core'] = $normal_dashboard;
  $wp_meta_boxes['dashboard']['side']['core'] = $side_dashboard;
}

function remove_footer_admin () {
  echo '<a href="http://wordpress.org">Web Design</a> by Jacob Tyler.';
}
add_filter('admin_footer_text', 'remove_footer_admin');

/* Adds even, odd, and last class to posts */
function oddeven_post_class ( $classes ) {
   global $current_class,$wp_query;
  
  if( ($wp_query->current_post + 1) < ($wp_query->post_count) ) { 
     $classes[] = $current_class;
  }else{
    $classes[] = $current_class . " last";
  }

  $current_class = ($current_class == 'odd') ? 'even' : 'odd';
   return $classes;
}
add_filter ( 'post_class' , 'oddeven_post_class' );
global $current_class;
$current_class = 'odd';

function filter_ptags_on_images($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

function admin_color_scheme() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}
add_action('admin_head', 'admin_color_scheme');

add_filter( 'enable_post_by_email_configuration', '__return_false' );

add_filter( 'ngettext', 'wps_remove_theme_name' );
if(!function_exists('wps_remove_theme_name')) {
function wps_remove_theme_name($translated) {
     $translated = str_ireplace('Theme <span class="b">%1$s</span> with',  '',  $translated );
     return $translated;
  }
}

function restrict_access_admin_panel(){
                global $current_user;
                get_currentuserinfo();
                if ($current_user->user_level <  4) {
                        wp_redirect( get_bloginfo('url') );
                        exit;
                }
        }
        add_action('admin_init', 'restrict_access_admin_panel', 1);


        function themeit_mce_buttons_2( $buttons ) {
  array_unshift( $buttons, 'styleselect' );
  return $buttons;
}
add_filter( 'mce_buttons_2', 'themeit_mce_buttons_2' );
function themeit_tiny_mce_before_init( $settings ) {
  $settings['theme_advanced_blockformats'] = 'p,a,div,span,h1,h2,h3,h4,h5,h6,tr,';
  $style_formats = array(
      array( 'title' => 'Button',         'inline' => 'span',  'classes' => 'button' ),
      array( 'title' => 'Green Button',   'inline' => 'span',  'classes' => 'button button-green' ),
      array( 'title' => 'Rounded Button', 'inline' => 'span',  'classes' => 'button button-rounded' ),
      array( 'title' => 'Other Options' ),
      array( 'title' => '&frac12; Col.',      'block'    => 'div',  'classes' => 'one-half' ),
      array( 'title' => '&frac12; Col. Last', 'block'    => 'div',  'classes' => 'one-half last' ),
      array( 'title' => 'Callout Box',        'block'    => 'div',  'classes' => 'callout-box' ),
      array( 'title' => 'Highlight',          'inline'   => 'span', 'classes' => 'highlight' )
  );
  $settings['style_formats'] = json_encode( $style_formats );
  return $settings;
}
add_filter( 'tiny_mce_before_init', 'themeit_tiny_mce_before_init' );


/*
function remove_editor_menu() {
  remove_action('admin_menu', '_add_themes_utility_last', 101);
}
add_action('_admin_menu', 'remove_editor_menu', 1);

function remove_submenus() {
  global $submenu;
  //Dashboard menu
  unset($submenu['index.php'][10]); // Removes Updates
  //Posts menu
  unset($submenu['edit.php'][5]); // Leads to listing of available posts to edit
  unset($submenu['edit.php'][10]); // Add new post
  unset($submenu['edit.php'][15]); // Remove categories
  unset($submenu['edit.php'][16]); // Removes Post Tags
  //Media Menu
  unset($submenu['upload.php'][5]); // View the Media library
  unset($submenu['upload.php'][10]); // Add to Media library
  //Links Menu
  unset($submenu['link-manager.php'][5]); // Link manager
  unset($submenu['link-manager.php'][10]); // Add new link
  unset($submenu['link-manager.php'][15]); // Link Categories
  //Pages Menu
  unset($submenu['edit.php?post_type=page'][5]); // The Pages listing
  unset($submenu['edit.php?post_type=page'][10]); // Add New page
  //Appearance Menu
  unset($submenu['themes.php'][5]); // Removes 'Themes'
  unset($submenu['themes.php'][7]); // Widgets
  unset($submenu['themes.php'][15]); // Removes Theme Installer tab
  //Plugins Menu
  unset($submenu['plugins.php'][5]); // Plugin Manager
  unset($submenu['plugins.php'][10]); // Add New Plugins
  unset($submenu['plugins.php'][15]); // Plugin Editor
  //Users Menu
  unset($submenu['users.php'][5]); // Users list
  unset($submenu['users.php'][10]); // Add new user
  unset($submenu['users.php'][15]); // Edit your profile
  //Tools Menu
  unset($submenu['tools.php'][5]); // Tools area
  unset($submenu['tools.php'][10]); // Import
  unset($submenu['tools.php'][15]); // Export
  unset($submenu['tools.php'][20]); // Upgrade plugins and core files
  //Settings Menu
  unset($submenu['options-general.php'][10]); // General Options
  unset($submenu['options-general.php'][15]); // Writing
  unset($submenu['options-general.php'][20]); // Reading
  unset($submenu['options-general.php'][25]); // Discussion
  unset($submenu['options-general.php'][30]); // Media
  unset($submenu['options-general.php'][35]); // Privacy
  unset($submenu['options-general.php'][40]); // Permalinks
  unset($submenu['options-general.php'][45]); // Misc
}
add_action('admin_menu', 'remove_submenus');

*/