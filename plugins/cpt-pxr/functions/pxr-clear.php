<?php

/**
 * Disable dashicons and admin bar
 */
if (isset(get_option('pxr_performance_options')['pxr_dashicons_show']) && get_option('pxr_performance_options')['pxr_dashicons_show'] == 'disable') {
   $pxr_dashicons_show = false;
}
if ($pxr_dashicons_show === false) {
   add_filter('show_admin_bar', '__return_false');
   remove_action('init', 'wp_admin_bar_init');
}
if (!function_exists('pxr_adbar_deregister_styles') && $pxr_dashicons_show === false) {
   function pxr_adbar_deregister_styles()
   {
      wp_deregister_style('dashicons');
   }
   add_action('wp_print_styles', 'pxr_adbar_deregister_styles', 100);
}


/**
 * Disable emojis in WordPress
 */
if (isset(get_option('pxr_performance_options')['pxr_emoji_show']) && get_option('pxr_performance_options')['pxr_emoji_show'] === 'disable') {
   $pxr_emoji_show = false;
}
if (!function_exists('pxr_disable_emojis') && $pxr_emoji_show === false) {
   function pxr_disable_emojis()
   {
      remove_action('wp_head', 'print_emoji_detection_script', 7);
      remove_action('admin_print_scripts', 'print_emoji_detection_script');
      remove_action('wp_print_styles', 'print_emoji_styles');
      remove_filter('the_content_feed', 'wp_staticize_emoji');
      remove_action('admin_print_styles', 'print_emoji_styles');
      remove_filter('comment_text_rss', 'wp_staticize_emoji');
      remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
      add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
   }
   add_action('init', 'pxr_disable_emojis');
}

if (!function_exists('disable_emojis_tinymce') && $pxr_emoji_show === false) {
   function disable_emojis_tinymce($plugins)
   {
      if (is_array($plugins)) {
         return array_diff($plugins, array('wpemoji'));
      } else {
         return array();
      }
   }
}


/**
 * Remove wp-embed.min.js
 */
if (isset(get_option('pxr_performance_options')['pxr_embed_show']) && get_option('pxr_performance_options')['pxr_embed_show'] == 'disable') {
   $pxr_embed_show = false;
}
if (!function_exists('pxr_embed_deregister_scripts') && $pxr_embed_show === false) {
   function pxr_embed_deregister_scripts()
   {
      wp_deregister_script('wp-embed');
   }
   add_action('wp_footer', 'pxr_embed_deregister_scripts');

   add_action('init', function () {

      remove_action('rest_api_init', 'wp_oembed_register_route');
      remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
      remove_action('wp_head', 'wp_oembed_add_discovery_links');
      remove_action('wp_head', 'wp_oembed_add_host_js');
   }, PHP_INT_MAX - 1);
}


/**
 * Remove Jquery migrate
 */
if (isset(get_option('pxr_performance_options')['pxr_migrate_show']) && get_option('pxr_performance_options')['pxr_migrate_show'] == 'disable') {
   $pxr_migrate_show = false;
}
if (!function_exists('pxr_dequeue_jquery_migrate') && $pxr_migrate_show === false) {
   function pxr_dequeue_jquery_migrate($scripts)
   {
      if (!is_admin() && !empty($scripts->registered['jquery'])) {
         $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
         );
      }
   }
   add_action('wp_default_scripts', 'pxr_dequeue_jquery_migrate');
}


/**
 * Remove application/rss+xml
 */
if (isset(get_option('pxr_performance_options')['pxr_rssxml_show']) && get_option('pxr_performance_options')['pxr_rssxml_show'] == 'disable') {
   $pxr_rssxml_show = false;
}
if ($pxr_rssxml_show === false) {
   remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
   remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
   remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
   remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
   remove_action('wp_head', 'index_rel_link'); // index link
   remove_action('wp_head', 'parent_post_rel_link', 10, 0); // prev link
   remove_action('wp_head', 'start_post_rel_link', 10, 0); // start link
   remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
   remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
}


/**
 * Remove shortlinks
 */
if (isset(get_option('pxr_performance_options')['pxr_shortinks_show']) && get_option('pxr_performance_options')['pxr_shortinks_show'] == 'disable') {
   $pxr_shortinks_show = false;
}
if ($pxr_shortinks_show === false) {
   remove_action('wp_head', 'wp_shortlink_wp_head');
}
