<?php
/**
 * Plugin Name: OneGodian Premium VC Pages
 * Description: Scoped stylesheet for seven premium OneGodian.org WPBakery root pages.
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Author: ONEGODIAN, LLC
 */
if (!defined('ABSPATH')) { exit; }
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_page') || !is_page(array('about', 'identity', 'learn', 'time', 'community', 'ecosystem', 'support'))) {
        return;
    }
    wp_enqueue_style(
        'onegodian-premium-vc-pages',
        plugins_url('assets/premium-seven.css', __FILE__),
        array(),
        '1.0.0',
        'all'
    );
}, 20);
