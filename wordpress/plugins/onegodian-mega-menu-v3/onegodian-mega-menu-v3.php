<?php
/**
 * Plugin Name: OneGodian Mega Menu v3
 * Description: Reversible 7 × 6 × 7 enterprise navigation for the existing OneGodian.org WordPress menu.
 * Version: 3.1.0
 * Requires PHP: 7.4
 * Text Domain: onegodian-mega-menu-v3
 * Author: ONEGODIAN, LLC
 *
 * @package OneGodianMegaMenuV3
 */

defined('ABSPATH') || exit;

define('OGMM_V3_VERSION', '3.1.0');
define('OGMM_V3_DIR', plugin_dir_path(__FILE__));
define('OGMM_V3_URL', plugin_dir_url(__FILE__));

require_once OGMM_V3_DIR . 'includes/class-ogmm-v3-admin.php';

/** Canonical root sections (navigation only; INO governance is not delegated here). */
function ogmm_v3_areas() {
    return array('ABOUT', 'IDENTITY', 'LEARN', 'COMMUNITY', 'ECOSYSTEM', 'SUPPORT', 'MEDIA CENTER');
}

function ogmm_v3_settings() {
    $stored = get_option('ogmm_v3_settings', array());
    return wp_parse_args(is_array($stored) ? $stored : array(), array(
        'menu_id' => 0,
        'enabled' => false,
    ));
}

function ogmm_v3_assets() {
    wp_enqueue_style('onegodian-mega-menu-v3', OGMM_V3_URL . 'assets/mega-menu-v3.css', array(), OGMM_V3_VERSION);
    wp_enqueue_script('onegodian-mega-menu-v3', OGMM_V3_URL . 'assets/mega-menu-v3.js', array(), OGMM_V3_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ogmm_v3_assets');

/** The shortcode can be inserted into LA-Studio Header Builder when the theme bypasses wp_nav_menu. */
function ogmm_v3_shortcode() {
    $settings = ogmm_v3_settings();
    return ogmm_v3_render((int) $settings['menu_id']);
}
add_shortcode('onegodian_mega_menu_v3', 'ogmm_v3_shortcode');

/** Select only the configured existing WordPress nav menu, leaving every other menu untouched. */
function ogmm_v3_replace_selected_menu($html, $args) {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return $html;
    }
    $settings = ogmm_v3_settings();
    if (empty($settings['enabled']) || empty($settings['menu_id']) || !is_object($args)) {
        return $html;
    }
    $selected = (int) $settings['menu_id'];
    $actual = 0;
    if (!empty($args->menu)) {
        $menu = wp_get_nav_menu_object($args->menu);
        $actual = $menu && !is_wp_error($menu) ? (int) $menu->term_id : 0;
    } elseif (!empty($args->theme_location)) {
        $locations = get_nav_menu_locations();
        $actual = isset($locations[$args->theme_location]) ? (int) $locations[$args->theme_location] : 0;
    }
    if ($actual !== $selected) {
        return $html;
    }
    $markup = ogmm_v3_render($selected);
    return $markup !== '' ? $markup : $html;
}
add_filter('wp_nav_menu', 'ogmm_v3_replace_selected_menu', 20, 2);

/** Canonical access labels are advisory; real authorization stays with the Members plugin. */
function ogmm_v3_protected_paths() {
    static $paths = null;
    if ($paths !== null) {
        return $paths;
    }
    $paths = array();
    $file = OGMM_V3_DIR . 'data/managed-pages.json';
    $manifest = is_readable($file) ? json_decode((string) file_get_contents($file), true) : null;
    foreach (is_array($manifest) ? ($manifest['entries'] ?? array()) : array() as $entry) {
        if (!empty($entry['login_required']) && !empty($entry['slug'])) {
            $paths[trim((string) $entry['slug'], '/')] = true;
        }
    }
    return $paths;
}

function ogmm_v3_valid_link($url) {
    return is_string($url) && $url !== '' && $url !== '#' && $url !== 'http://#' && $url !== 'https://#';
}

function ogmm_v3_link_markup($item, $kind = 'link') {
    $url = isset($item->url) ? (string) $item->url : '';
    if (!ogmm_v3_valid_link($url)) {
        return '';
    }
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    $protected = isset(ogmm_v3_protected_paths()[$path]);
    $target = isset($item->target) && $item->target === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';
    return '<a class="ogmm-v3__' . esc_attr($kind) . '" href="' . esc_url($url) . '"' . $target . '>'
        . '<span>' . esc_html((string) $item->title) . '</span>'
        . ($protected ? '<span class="ogmm-v3__lock" aria-label="Sign-in required">Member</span>' : '')
        . '<span class="ogmm-v3__link-arrow" aria-hidden="true">↗</span></a>';
}

/** Retain existing nested utility links outside the seven canonical primary areas. */
function ogmm_v3_render_utility_children($parent_id, $children, $depth = 0, $visited = array()) {
    if ($depth > 12 || isset($visited[$parent_id]) || empty($children[$parent_id])) {
        return '';
    }
    $visited[$parent_id] = true;
    $markup = '<ul class="ogmm-v3__utility-list">';
    foreach ($children[$parent_id] as $item) {
        $link = ogmm_v3_link_markup($item, 'utility-sub-link');
        $nested = ogmm_v3_render_utility_children((int) $item->ID, $children, $depth + 1, $visited);
        $markup .= '<li>' . ($link !== '' ? $link : '<span>' . esc_html((string) $item->title) . '</span>') . $nested . '</li>';
    }
    return $markup . '</ul>';
}

/**
 * Render real WordPress menu items, including pre-existing unrelated utility roots.
 * Never synthesize unverified links or silently discard overflow.
 */
function ogmm_v3_render($menu_id) {
    $menu = $menu_id ? wp_get_nav_menu_object($menu_id) : false;
    if (!$menu || is_wp_error($menu)) {
        return '';
    }
    $items = wp_get_nav_menu_items($menu->term_id, array('post_status' => 'publish'));
    if (!is_array($items)) {
        return '';
    }
    $children = array();
    foreach ($items as $item) {
        $parent = (int) $item->menu_item_parent;
        $children[$parent][] = $item;
    }
    $roots = isset($children[0]) ? $children[0] : array();
    $sections = array();
    $utilities = array();
    foreach ($roots as $root) {
        $key = strtoupper(trim((string) $root->title));
        if ($key === 'TIME') {
            // Preserve the retired WordPress root for rollback, but never display an eighth primary area.
            // Time tools are assigned to LEARN by the managed-page manifest.
            continue;
        }
        if (in_array($key, ogmm_v3_areas(), true) && !isset($sections[$key])) {
            $sections[$key] = $root;
        } else {
            $utilities[] = $root;
        }
    }
    if (empty($sections)) {
        return '';
    }
    static $instances = 0;
    $instances++;
    $id = 'ogmm-v3-' . $instances;
    $out = '<nav id="' . esc_attr($id) . '" class="ogmm-v3" aria-label="OneGodian enterprise navigation" data-ogmm-v3>'
        . '<div class="ogmm-v3__shell">'
        . '<button class="ogmm-v3__mobile-toggle" type="button" aria-expanded="false" aria-controls="' . esc_attr($id) . '-sections">'
        . '<span class="ogmm-v3__hamburger" aria-hidden="true"><i></i><i></i><i></i></span>'
        . '<span>Explore OneGodian</span></button>'
        . '<div class="ogmm-v3__navigation" id="' . esc_attr($id) . '-sections"><ul class="ogmm-v3__roots">';

    foreach (ogmm_v3_areas() as $area) {
        if (!isset($sections[$area])) {
            continue;
        }
        $root = $sections[$area];
        $direct = $children[(int) $root->ID] ?? array();
        $columns = array();
        $quick = array();
        if (ogmm_v3_valid_link((string) $root->url)) {
            $quick[] = $root;
        }
        // A child with descendants represents a semantic heading/column.
        foreach ($direct as $child) {
            $descendants = $children[(int) $child->ID] ?? array();
            if (!$descendants) {
                $quick[] = $child;
                continue;
            }
            $links = array();
            if (ogmm_v3_valid_link((string) $child->url)) {
                $links[] = $child;
            }
            // Preserve 3rd+ level links rather than dropping deeper existing navigation.
            $stack = $descendants;
            $visited = array();
            while ($stack) {
                $leaf = array_shift($stack);
                if (isset($visited[$leaf->ID])) {
                    continue;
                }
                $visited[$leaf->ID] = true;
                $links[] = $leaf;
                foreach ($children[(int) $leaf->ID] ?? array() as $nested) {
                    $stack[] = $nested;
                }
            }
            foreach (array_chunk($links, 7) as $i => $chunk) {
                $columns[] = array('heading' => (string) $child->title . ($i ? ' — continued' : ''), 'links' => $chunk);
            }
        }
        if ($quick) {
            $quick_columns = array();
            foreach (array_chunk($quick, 7) as $i => $chunk) {
                $quick_columns[] = array('heading' => $i ? 'Quick links — continued' : 'Quick links', 'links' => $chunk);
            }
            $columns = array_merge($quick_columns, $columns);
        }
        $count = count($columns);
        $panel_id = $id . '-panel-' . strtolower($area);
        $button_id = $id . '-trigger-' . strtolower($area);
        $out .= '<li class="ogmm-v3__item" data-ogmm-item>'
            . '<button class="ogmm-v3__trigger" type="button" id="' . esc_attr($button_id)
            . '" aria-expanded="false" aria-controls="' . esc_attr($panel_id) . '">'
            . esc_html($area) . '<span class="ogmm-v3__chevron" aria-hidden="true"></span></button>'
            . '<div class="ogmm-v3__panel" id="' . esc_attr($panel_id) . '" role="region" aria-labelledby="' . esc_attr($button_id) . '">'
            . '<div class="ogmm-v3__panel-intro"><span class="ogmm-v3__eyebrow">ONEGODIAN • EXPLORE</span>'
            . '<h2>' . esc_html(ucfirst(strtolower($area))) . '</h2><span class="ogmm-v3__intro-line" aria-hidden="true"></span></div>'
            . '<div class="ogmm-v3__columns" style="--ogmm-columns:' . esc_attr((string) min(6, max(1, $count))) . '">';
        foreach ($columns as $column) {
            $out .= '<section class="ogmm-v3__column"><h3>' . esc_html($column['heading']) . '</h3><ul>';
            foreach ($column['links'] as $leaf) {
                $link = ogmm_v3_link_markup($leaf);
                if ($link !== '') {
                    $out .= '<li>' . $link . '</li>';
                }
            }
            $out .= '</ul></section>';
        }
        if (!$count) {
            $out .= '<p class="ogmm-v3__empty">Links appear when published pages are available.</p>';
        }
        $out .= '</div></div></li>';
    }
    $out .= '</ul>';
    if ($utilities) {
        $out .= '<div class="ogmm-v3__utilities" aria-label="Additional navigation">';
        foreach ($utilities as $utility) {
            $nested = ogmm_v3_render_utility_children((int) $utility->ID, $children);
            if ($nested !== '') {
                $out .= '<details class="ogmm-v3__utility-menu"><summary>' . esc_html((string) $utility->title) . '</summary>';
                $out .= '<div class="ogmm-v3__utility-popup">';
                if (ogmm_v3_valid_link((string) $utility->url)) {
                    $out .= ogmm_v3_link_markup($utility, 'utility-sub-link');
                }
                $out .= $nested . '</div></details>';
            } else {
                $out .= ogmm_v3_link_markup($utility, 'utility');
            }
        }
        $out .= '</div>';
    }
    $out .= '</div></div></nav>';
    return $out;
}
