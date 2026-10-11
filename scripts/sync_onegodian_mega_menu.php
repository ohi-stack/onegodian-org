<?php
/**
 * OneGodian.org mega-menu synchronizer.
 *
 * Run after a database backup and staging acceptance:
 * ONEGODIAN_MENU_LOCATION=primary ONEGODIAN_MENU_DRY_RUN=1 \
 *   wp eval-file scripts/sync_onegodian_mega_menu.php
 * ONEGODIAN_MENU_LOCATION=primary \
 *   wp eval-file scripts/sync_onegodian_mega_menu.php
 *
 * Alternatively use ONEGODIAN_MENU_ID=<numeric menu term ID>.
 *
 * This deliberately does NOT delete menu items, create content pages, or
 * implement authentication. It only adds the requested navigation links after
 * the owning plugin has published each page and registered its shortcode.
 */

if (!defined('ABSPATH') || !defined('WP_CLI') || !WP_CLI) {
    fwrite(STDERR, "Run this file inside WP-CLI using wp eval-file.\n");
    return;
}

$manifest_path = dirname(__DIR__) . '/config/onegodian-org-mega-menu.json';
if (!is_readable($manifest_path)) {
    WP_CLI::error('Navigation manifest not found: ' . $manifest_path);
}
$manifest = json_decode((string) file_get_contents($manifest_path), true);
if (!is_array($manifest) || json_last_error() !== JSON_ERROR_NONE) {
    WP_CLI::error('Navigation manifest is not valid JSON.');
}
$entries = $manifest['entries'] ?? null;
$areas = $manifest['top_level'] ?? null;
if (!is_array($entries) || count($entries) !== 43 || !is_array($areas) || count($areas) !== 7) {
    WP_CLI::error('Expected exactly 43 page entries and seven navigation areas.');
}

$raw_id = (string) getenv('ONEGODIAN_MENU_ID');
$location = trim((string) getenv('ONEGODIAN_MENU_LOCATION'));
$dry_run = in_array(strtolower(trim((string) getenv('ONEGODIAN_MENU_DRY_RUN'))), array('1', 'true', 'yes'), true);
if ($raw_id !== '' && ctype_digit($raw_id)) {
    $menu = wp_get_nav_menu_object((int) $raw_id);
} elseif ($location !== '') {
    $locations = get_nav_menu_locations();
    $menu = isset($locations[$location]) ? wp_get_nav_menu_object($locations[$location]) : false;
} else {
    WP_CLI::error('Select the existing WordPress menu explicitly using ONEGODIAN_MENU_ID or ONEGODIAN_MENU_LOCATION. No menu was changed.');
}
if (!$menu || is_wp_error($menu)) {
    WP_CLI::error('Selected WordPress navigation menu does not exist. No menu was changed.');
}

$items = wp_get_nav_menu_items($menu->term_id, array('post_status' => 'any'));
$items = is_array($items) ? $items : array();
$counter = array('added' => 0, 'present' => 0, 'updated' => 0, 'skipped' => 0);
$seen_slugs = array();
$eligible = array();

foreach ($entries as $entry) {
    $slug = $entry['slug'] ?? '';
    $shortcode = $entry['shortcode'] ?? '';
    $area = $entry['area'] ?? '';
    $group = $entry['group'] ?? '';
    if (!is_string($slug) || !preg_match('#^/[a-z0-9-]+/$#', $slug)
        || !is_string($shortcode) || !preg_match('/^\[([a-z0-9_]+)\]$/', $shortcode, $match)
        || !in_array($area, $areas, true) || !is_string($group) || trim($group) === ''
        || isset($seen_slugs[$slug])) {
        WP_CLI::error('Invalid or duplicate canonical menu entry: ' . (string) $slug);
    }
    $seen_slugs[$slug] = true;
    $page = get_page_by_path(trim($slug, '/'), OBJECT, 'page');
    $page_ready = $page instanceof WP_Post && $page->post_status === 'publish';
    $shortcode_ready = shortcode_exists($match[1]);
    if (!$page_ready || !$shortcode_ready) {
        $counter['skipped']++;
        WP_CLI::warning(sprintf(
            'SKIP %s — page=%s, shortcode=%s (reported status: %s)',
            $slug,
            $page_ready ? 'published' : 'missing/unpublished',
            $shortcode_ready ? 'registered' : 'unregistered',
            $entry['reported_status'] ?? 'unknown'
        ));
        continue;
    }
    $eligible[$area][$group][] = $entry;
}

$virtual_id = -1;
$find = static function ($parent, $title) use (&$items) {
    foreach ($items as $item) {
        if ((int) $item->menu_item_parent === (int) $parent
            && strtolower(trim((string) $item->title)) === strtolower(trim((string) $title))) {
            return $item;
        }
    }
    return null;
};

$save = static function ($label, $parent, $url, $css, $existing = null) use (&$items, &$virtual_id, &$counter, $menu, $dry_run) {
    $args = array(
        'menu-item-title' => $label,
        'menu-item-url' => $url,
        'menu-item-type' => 'custom',
        'menu-item-status' => 'publish',
        'menu-item-parent-id' => $parent,
        'menu-item-classes' => (array) $css,
    );
    $id = $existing ? (int) $existing->ID : 0;
    if ($existing && rtrim((string) $existing->url, '/') === rtrim($url, '/')) {
        $counter['present']++;
        return $id;
    }
    if ($dry_run) {
        $id = $existing ? $id : $virtual_id--;
        WP_CLI::log(sprintf('DRY RUN %s %s → %s', $existing ? 'UPDATE' : 'ADD', $label, $url));
    } else {
        $id = wp_update_nav_menu_item((int) $menu->term_id, $id, $args);
        if (is_wp_error($id) || !$id) {
            WP_CLI::error('Failed to synchronize menu item: ' . $label);
        }
    }
    $counter[$existing ? 'updated' : 'added']++;
    if (!$existing) {
        $items[] = (object) array(
            'ID' => $id,
            'menu_item_parent' => (string) $parent,
            'title' => $label,
            'url' => $url,
        );
    }
    return (int) $id;
};

foreach ($areas as $area) {
    if (empty($eligible[$area])) {
        continue;
    }
    $root = $find(0, $area);
    // Existing top-level items keep their original destinations and theme classes.
    $root_id = $root ? (int) $root->ID
        : $save($area, 0, '#', array('onegodian-mega-root'));
    foreach ($eligible[$area] as $group_name => $links) {
        $group_item = $find($root_id, $group_name);
        $group_id = $group_item ? (int) $group_item->ID
            : $save($group_name, $root_id, '#', array('onegodian-mega-group'));
        foreach ($links as $entry) {
            $existing = $find($group_id, $entry['label']);
            $classes = array('onegodian-managed-link');
            if (!empty($entry['login_required'])) {
                $classes[] = 'onegodian-login-required';
            }
            $save($entry['label'], $group_id, home_url($entry['slug']), $classes, $existing);
        }
    }
}

WP_CLI::success(sprintf(
    '%s: menu=%s (#%d), added=%d, already-present=%d, updated=%d, skipped=%d. Missing or unregistered pages must be repaired and this command re-run.',
    $dry_run ? 'Dry run complete' : 'Sync complete',
    $menu->name,
    $menu->term_id,
    $counter['added'],
    $counter['present'],
    $counter['updated'],
    $counter['skipped']
));
