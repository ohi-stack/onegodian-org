<?php
/**
 * Plugin Name: OneGodian Premium VC Pages
 * Description: Scoped WPBakery page styles for seven main OneGodian.org sections, What Is OneGodian, and the Remember Campaign.
 * Version: 1.2.0
 * Requires PHP: 7.4
 * Author: ONEGODIAN, LLC
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_page') || !is_page()) {
        return;
    }

    $seven_pages = array('about', 'identity', 'learn', 'time', 'community', 'ecosystem', 'support');
    if (is_page($seven_pages)) {
        wp_enqueue_style(
            'onegodian-premium-vc-pages',
            plugins_url('assets/premium-seven.css', __FILE__),
            array(),
            '1.2.0',
            'all'
        );
    }

    if (is_page('what-is-onegodian')) {
        wp_enqueue_style(
            'onegodian-what-is-onegodian',
            plugins_url('assets/what-is-onegodian.css', __FILE__),
            array(),
            '1.2.0',
            'all'
        );
    }

    // Follow the page's unique VC marker so updates work on an existing
    // Remember page regardless of its current WordPress slug or page ID.
    $post_id = get_queried_object_id();
    $page_content = $post_id ? (string) get_post_field('post_content', $post_id) : '';
    if (strpos($page_content, 'ogrm-hero') !== false) {
        wp_enqueue_style(
            'onegodian-remember-campaign',
            plugins_url('assets/onegodian-remember.css', __FILE__),
            array(),
            '1.2.0',
            'all'
        );
    }
}, 20);
