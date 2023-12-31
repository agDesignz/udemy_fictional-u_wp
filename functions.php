<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/contact.php');
require get_theme_file_path('/inc/like-route.php');

function university_custom_rest() {
  register_rest_field('post', 'authorName', [
      'get_callback' => function() {return get_the_author();}
    ]); // 3 args('post type', 'ourNameForIt', '['how' => 'manage field']')
}

add_action('rest_api_init', 'university_custom_rest'); // 2 args: ('hook or point at which we add it', 'func to call')

function pageBanner($args = NULL) {

  if(!isset($args['title'])) {
    $args['title'] = get_the_title();
  }
  if(!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }
  if (!isset($args['photo'])) {
    if(get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
          $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
  }

  ?>
  <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
          <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
          <div class="page-banner__intro">
            <p><?php echo $args['subtitle']; ?></p>
          </div>
        </div>
      </div>
  <?php
}

function university_files() {
  wp_enqueue_style('leaflet-map-css', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
  wp_enqueue_script('leaflet-map-js', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',  NULL, '1.0.1', true);
  wp_enqueue_script('main_university_javascript', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('custom_google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

  // Eneble JS to access the relative path url (from root) for REST API json request
  wp_localize_script('main_university_javascript', 'universityData', [
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ]);

}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
  register_nav_menu('headerMenuLocation', 'Header Menu Location');
  register_nav_menu('footerLocationOne', 'Footer Location One');
  register_nav_menu('footerLocationTwo', 'Footer Location Two');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
  if (!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);

  }

  if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', [
      [
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      ]
    ]);
  }
}

add_action('pre_get_posts', 'university_adjust_queries');

// Send email via SMTP
add_action( 'phpmailer_init', 'my_phpmailer_example' );
function my_phpmailer_example( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host = SMTP_HOST;
    $phpmailer->SMTPAuth = SMTP_AUTH;
    $phpmailer->Port = SMTP_PORT;
    $phpmailer->Username = SMTP_USER;
    $phpmailer->Password = SMTP_PASS;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->From = SMTP_FROM;
    $phpmailer->FromName = SMTP_NAME;
}

// REDIRECT SUBSCRIBER ACCTS TO HOMEPAGE
add_action('admin_init', 'redirectSubsToFrontend');
function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();
  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action('wp_loaded', 'noSubsAdminBar');
function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();
  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// CUSTOMIZE LOGIN SCREEN
add_filter('login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

add_filter('login_headertitle', 'ourLoginTitle');
function ourLoginTitle() {
    return get_bloginfo('name');
}

add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS() {
  wp_enqueue_style('custom_google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

// FORCE NOTE POSTS TO BE PRIVATE, SANITIZING INPUT, SETTIMG LIMITS

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if ($data['post_type'] == 'note') {

    // per-user post limit
    if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
      die("You have reached your note limit.");
    }

    // sanitizing user input so they can't post code
    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_text_field($data['post_title']);
  }

  // forcing 'private' status
  if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
    $data['post_status'] = "private";
  }
  return $data;
}

// PER-USER POST LIMIT
