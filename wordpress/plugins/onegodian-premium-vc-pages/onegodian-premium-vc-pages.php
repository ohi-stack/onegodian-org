<?php
/**
 * Plugin Name: OneGodian Premium VC Pages
 * Description: Scoped WPBakery page styles for seven main OneGodian.org sections and the What Is OneGodian explainer.
 * Version: 1.1.0
 * Requires PHP: 7.4
 * Author: ONEGODIAN, LLC
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_page')) {
        return;
    }

    $seven_pages = array('about', 'identity', 'learn', 'time', 'community', 'ecosystem', 'support');

    if (is_page($seven_pages)) {
        wp_enqueue_style(
            'onegodian-premium-vc-pages',
            plugins_url('assets/premium-seven.css', __FILE__),
            array(),
            '1.1.0',
            'all'
        );
    }

    if (is_page('what-is-onegodian')) {
        wp_enqueue_style(
            'onegodian-what-is-onegodian',
            plugins_url('assets/what-is-onegodian.css', __FILE__),
            array(),
            '1.1.0',
            'all'
        );
    }
}, 20);
