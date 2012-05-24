<?php

if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
  wp_redirect(admin_url('themes.php?page=theme_activation_options'));
  exit;
}

function roots_theme_activation_options_init() {
  if (roots_get_theme_activation_options() === false) {
    add_option('roots_theme_activation_options', roots_get_default_theme_activation_options());
  }

  register_setting(
    'roots_activation_options',
    'roots_theme_activation_options',
    'roots_theme_activation_options_validate'
  );
}

add_action('admin_init', 'roots_theme_activation_options_init');

function roots_activation_options_page_capability($capability) {
  return 'edit_theme_options';
}

add_filter('option_page_capability_roots_activation_options', 'roots_activation_options_page_capability');

function roots_theme_activation_options_add_page() {
  $roots_activation_options = roots_get_theme_activation_options();
  if (!$roots_activation_options['first_run']) {
    $theme_page = add_theme_page(
      __('Theme Activation', 'roots'),
      __('Theme Activation', 'roots'),
      'edit_theme_options',
      'theme_activation_options',
      'roots_theme_activation_options_render_page'
    );
  } else {
    if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'theme_activation_options') {
      wp_redirect(admin_url('themes.php'));
      exit;
    }
  }

}

add_action('admin_menu', 'roots_theme_activation_options_add_page', 50);

function roots_get_default_theme_activation_options() {
  $default_theme_activation_options = array(
    'first_run'                       => false,
    'create_front_page'               => false,
    'change_permalink_structure'      => false,
    'change_uploads_folder'           => false,
    'create_navigation_menus'         => false,
    'add_pages_to_primary_navigation' => false,
  );

  return apply_filters('roots_default_theme_activation_options', $default_theme_activation_options);
}

function roots_get_theme_activation_options() {
  return get_option('roots_theme_activation_options', roots_get_default_theme_activation_options());
}

function roots_theme_activation_options_render_page() { ?>

  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php printf(__('%s Theme Activation', 'roots'), get_current_theme()); ?></h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">

      <?php
        settings_fields('roots_activation_options');
        $roots_activation_options = roots_get_theme_activation_options();
        $roots_default_activation_options = roots_get_default_theme_activation_options();
      ?>

      <input type="hidden" value="1" name="roots_theme_activation_options[first_run]" />

      <table class="form-table">
        <?php $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false ); ?>
        <tr valign="top"><th scope="row"><?php _e('Create JT Admin User?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create JT Admin User?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[create_jt_admin]" id="create_jt_admin">
                <option value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option selected="selected" value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description">Save this log-in information: U:jtcg P:<?php echo $random_password; ?>. A copy will be sent to wpadmin@jacobtyler.com.</small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Create static front page?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create static front page?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[create_front_page]" id="create_front_page">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Create a page called Home and set it to be the static front page', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Create template pages?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create template pages?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[create_template_pages]" id="create_template_pages">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <?php $default_pages = array_diff(scandir(get_stylesheet_directory().'/inc/pages/'), array('..', '.','.DS_Store','.TemporaryItems','.com.apple.timemachine.supported','.htaccess','.localized','.svn','index.php'));

$titleList = '';

foreach ($default_pages as $new_page_title) {
  $title = str_replace(array('page-','post-','.html'), ' ', $new_page_title);
  $titleList .= str_replace('_', ' ', $title);
} ?>
              <small class="description"><?php echo 'Create default pages? ( ' . $titleList .' )'; ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Change permalink structure?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Update permalink structure?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[change_permalink_structure]" id="change_permalink_structure">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Change permalink structure to /&#37;postname&#37;/', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Change uploads folder?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Update uploads folder?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[change_uploads_folder]" id="change_uploads_folder">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Change uploads folder to /assets/ instead of /wp-content/uploads/', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Create navigation menu?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Create navigation menu?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[create_navigation_menus]" id="create_navigation_menus">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Create the Primary Navigation menu and set the location', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Add pages to menu?', 'roots'); ?></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span><?php _e('Add pages to menu?', 'roots'); ?></span></legend>
              <select name="roots_theme_activation_options[add_pages_to_primary_navigation]" id="add_pages_to_primary_navigation">
                <option selected="selected" value="yes"><?php echo _e('Yes', 'roots'); ?></option>
                <option value="no"><?php echo _e('No', 'roots'); ?></option>
              </select>
              <br />
              <small class="description"><?php printf(__('Add all current published pages to the Primary Navigation', 'roots')); ?></small>
            </fieldset>
          </td>
        </tr>

      </table>

      <?php submit_button(); ?>
    </form>
  </div>

<?php }

function roots_theme_activation_options_validate($input) {
  $output = $defaults = roots_get_default_theme_activation_options();

  if (isset($input['first_run'])) {
    if ($input['first_run'] === '1') {
      $input['first_run'] = true;
    }
    $output['first_run'] = $input['first_run'];
  }

  if (isset($input['create_front_page'])) {
    if ($input['create_front_page'] === 'yes') {
      $input['create_front_page'] = true;
    }
    if ($input['create_front_page'] === 'no') {
      $input['create_front_page'] = false;
    }
    $output['create_front_page'] = $input['create_front_page'];
  }

  if (isset($input['change_permalink_structure'])) {
    if ($input['change_permalink_structure'] === 'yes') {
      $input['change_permalink_structure'] = true;
    }
    if ($input['change_permalink_structure'] === 'no') {
      $input['change_permalink_structure'] = false;
    }
    $output['change_permalink_structure'] = $input['change_permalink_structure'];
  }

  if (isset($input['create_jt_admin'])) {
    if ($input['create_jt_admin'] === 'yes') {
      $input['create_jt_admin'] = true;
    }
    if ($input['create_jt_admin'] === 'no') {
      $input['create_jt_admin'] = false;
    }
    $output['create_jt_admin'] = $input['create_jt_admin'];
  }

  if (isset($input['change_uploads_folder'])) {
    if ($input['change_uploads_folder'] === 'yes') {
      $input['change_uploads_folder'] = true;
    }
    if ($input['change_uploads_folder'] === 'no') {
      $input['change_uploads_folder'] = false;
    }
    $output['change_uploads_folder'] = $input['change_uploads_folder'];
  }

  if (isset($input['create_navigation_menus'])) {
    if ($input['create_navigation_menus'] === 'yes') {
      $input['create_navigation_menus'] = true;
    }
    if ($input['create_navigation_menus'] === 'no') {
      $input['create_navigation_menus'] = false;
    }
    $output['create_navigation_menus'] = $input['create_navigation_menus'];
  }

  if (isset($input['add_pages_to_primary_navigation'])) {
    if ($input['add_pages_to_primary_navigation'] === 'yes') {
      $input['add_pages_to_primary_navigation'] = true;
    }
    if ($input['add_pages_to_primary_navigation'] === 'no') {
      $input['add_pages_to_primary_navigation'] = false;
    }
    $output['add_pages_to_primary_navigation'] = $input['add_pages_to_primary_navigation'];
  }

  return apply_filters('roots_theme_activation_options_validate', $output, $input, $defaults);
}

function roots_theme_activation_action() {
  $roots_theme_activation_options = roots_get_theme_activation_options();

if (is_admin() && isset($_GET['page']) && 'themes.php' == $GLOBALS['pagenow']) {
  if($roots_theme_activation_options['create_jt_admin']){
    $userdata = array (
      'user_url' => 'http://www.jacobtyler.com',
      'user_login' => 'jtcg', 
      'user_pass'=>$random_password, 
      'user_nicename' => 'Jacob Tyler', 
      'user_email' => 'wpadmin@jacobtyler.com', 
      'display_name' => 'Jacob Tyler', 
      'nickname' => 'Dev Team',
      'first_name' => 'Jacob',
      'last_name' => 'Tyler', 
      'role'=> 'administrator'
      );


    $new_user = wp_insert_user( $userdata );
    if(is_int($new_user)){
      wp_new_user_notification( $new_user, $random_password );
    }
  }
}
  if ($roots_theme_activation_options['create_front_page']) {
    $roots_theme_activation_options['create_front_page'] = false;

  if($roots_theme_activation_options['create_template_pages']){

	$default_pages = array_diff(scandir(get_stylesheet_directory().'/inc/pages/'), array('..', '.','.DS_Store','.TemporaryItems','.com.apple.timemachine.supported','.htaccess','.localized','.svn','index.php'));
    $existing_pages = get_pages();
    $temp = array();

    foreach ($existing_pages as $page) {
      $temp[] = $page->post_title;
    }
	
	$pages_to_create = array_diff($default_pages, $temp);
	
    foreach ($pages_to_create as $new_page_title) {
		$page_content = file_get_contents(get_stylesheet_directory().'/inc/pages/'.$new_page_title,true);
		$pieces = explode("-", $new_page_title);
		$page_type = $pieces[0];
		
		$title = str_replace(array('page-','post-','.html'), ' ', $new_page_title);
		$title = str_replace('_', ' ', $title);
		$add_default_pages = array(
		'post_title' => $title,
		'post_content' => $page_content,
		'post_status' => 'publish',
		'post_type' => $page_type
		);

		$result = wp_insert_post($add_default_pages);
    }

    }

    $home = get_page_by_title('Home');
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home->ID);

    $home_menu_order = array(
      'ID' => $home->ID,
      'menu_order' => -1
    );
    wp_update_post($home_menu_order);
  }

  if ($roots_theme_activation_options['change_permalink_structure']) {
    $roots_theme_activation_options['change_permalink_structure'] = false;

    if (get_option('permalink_structure') !== '/%postname%/') {
      update_option('permalink_structure', '/%postname%/');
    }

    global $wp_rewrite;
    $wp_rewrite->init();
    $wp_rewrite->flush_rules();
  }

  if ($roots_theme_activation_options['change_uploads_folder']) {
    $roots_theme_activation_options['change_uploads_folder'] = false;

    update_option('uploads_use_yearmonth_folders', 0);
    update_option('upload_path', 'assets');
  }

  if ($roots_theme_activation_options['create_navigation_menus']) {
    $roots_theme_activation_options['create_navigation_menus'] = false;

    $roots_nav_theme_mod = false;

    if (!has_nav_menu('primary_navigation')) {
      $primary_nav_id = wp_create_nav_menu('Primary Navigation', array('slug' => 'primary_navigation'));
      $roots_nav_theme_mod['primary_navigation'] = $primary_nav_id;
    }

    if ($roots_nav_theme_mod) {
      set_theme_mod('nav_menu_locations', $roots_nav_theme_mod);
    }
  }

  if ($roots_theme_activation_options['add_pages_to_primary_navigation']) {
    $roots_theme_activation_options['add_pages_to_primary_navigation'] = false;

    $primary_nav = wp_get_nav_menu_object('Primary Navigation');
    $primary_nav_term_id = (int) $primary_nav->term_id;
    $menu_items= wp_get_nav_menu_items($primary_nav_term_id);
    if (!$menu_items || empty($menu_items)) {
      $pages = get_pages();
      foreach($pages as $page) {
        $item = array(
          'menu-item-object-id' => $page->ID,
          'menu-item-object' => 'page',
          'menu-item-type' => 'post_type',
          'menu-item-status' => 'publish'
        );
        wp_update_nav_menu_item($primary_nav_term_id, 0, $item);
      }
    }
  }
  update_option("default_comment_status","closed");
  update_option( 'thumbnail_size_h', 0 );
  update_option( 'thumbnail_size_w', 0 );
  update_option( 'medium_size_h', 0 );
  update_option( 'medium_size_w', 0 );
  update_option( 'large_size_h', 0 );
  update_option( 'large_size_w', 0 );
  update_option('roots_theme_activation_options', $roots_theme_activation_options);
}

add_action('admin_init','roots_theme_activation_action');

function roots_deactivation_action() {
  update_option('roots_theme_activation_options', roots_get_default_theme_activation_options());
}

add_action('switch_theme', 'roots_deactivation_action');