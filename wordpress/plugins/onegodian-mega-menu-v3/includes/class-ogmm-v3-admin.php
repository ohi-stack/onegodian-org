<?php
/**
 * WordPress controls for reversible OneGodian Mega Menu v3 rollout.
 *
 * The source WordPress menu, locations and unrelated links remain untouched.
 * Changes are additive; rollback deletes only records created and tagged by v3.
 */
defined('ABSPATH') || exit;

function ogmm_v3_manifest() {
    $file = OGMM_V3_DIR . 'data/managed-pages.json';
    if (!is_readable($file)) {
        return new WP_Error('ogmm_missing_manifest', 'The bundled October 31 page inventory is unavailable.');
    }
    $manifest = json_decode((string) file_get_contents($file), true);
    if (!is_array($manifest) || ($manifest['top_level'] ?? null) !== ogmm_v3_areas()
        || count($manifest['entries'] ?? array()) !== 46) {
        return new WP_Error('ogmm_invalid_manifest', 'The 46-page, seven-section source manifest failed validation.');
    }
    $seen = array();
    foreach ($manifest['entries'] as $entry) {
        $slug = $entry['slug'] ?? '';
        if (!is_string($slug) || !preg_match('#^/[a-z0-9-]+/$#', $slug)
            || isset($seen[$slug]) || !isset($entry['area'], $entry['group'], $entry['label'], $entry['shortcode'])
            || !in_array($entry['area'], ogmm_v3_areas(), true)
            || !preg_match('/^\[([a-z0-9_]+)\]$/', (string) $entry['shortcode'])) {
            return new WP_Error('ogmm_invalid_manifest', 'Invalid or duplicate page in the navigation manifest.');
        }
        $seen[$slug] = true;
    }
    return $manifest;
}

function ogmm_v3_find_item($items, $parent, $title) {
    foreach ($items as $item) {
        if ((int) $item->menu_item_parent === (int) $parent
            && strcasecmp(trim((string) $item->title), trim((string) $title)) === 0) {
            return $item;
        }
    }
    return null;
}

function ogmm_v3_has_url_in_area($items, $area_id, $url) {
    $under = array((int) $area_id);
    $seen = array();
    while ($under) {
        $parent = array_shift($under);
        if (isset($seen[$parent])) {
            continue;
        }
        $seen[$parent] = true;
        foreach ($items as $item) {
            if ((int) $item->menu_item_parent !== $parent) {
                continue;
            }
            if (untrailingslashit((string) $item->url) === untrailingslashit((string) $url)) {
                return true;
            }
            $under[] = (int) $item->ID;
        }
    }
    return false;
}

function ogmm_v3_menu_insert($menu_id, $parent_id, $label, $url, $classes, &$snapshot, &$items) {
    $new_id = wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => $label,
        'menu-item-url' => $url,
        'menu-item-type' => 'custom',
        'menu-item-parent-id' => $parent_id,
        'menu-item-status' => 'publish',
        'menu-item-classes' => $classes,
    ));
    if (is_wp_error($new_id) || !(int) $new_id) {
        return new WP_Error('ogmm_insert_failed', 'Could not create menu item: ' . $label);
    }
    $new_id = (int) $new_id;
    // Persist the rollback ledger after every successful write (including interrupted runs).
    update_post_meta($new_id, '_ogmm_v3_created_for_menu', (string) $menu_id);
    $snapshot['added_ids'][] = $new_id;
    update_option('ogmm_v3_snapshot', $snapshot, false);
    $items[] = (object) array(
        'ID' => $new_id, 'menu_item_parent' => (string) $parent_id,
        'title' => $label, 'url' => $url,
    );
    return $new_id;
}

/**
 * @return array|WP_Error count summary; commit=false is a read-only dry run.
 */
function ogmm_v3_populate($menu_id, $commit = false) {
    $menu = wp_get_nav_menu_object($menu_id);
    $manifest = ogmm_v3_manifest();
    if (!$menu || is_wp_error($menu) || is_wp_error($manifest)) {
        return is_wp_error($manifest) ? $manifest : new WP_Error('ogmm_missing_menu', 'Choose an existing WordPress menu.');
    }
    $snapshot = get_option('ogmm_v3_snapshot', array());
    if (!is_array($snapshot)) {
        $snapshot = array();
    }
    if (!empty($snapshot['menu_id']) && (int) $snapshot['menu_id'] !== (int) $menu_id) {
        return new WP_Error('ogmm_rollback_required', 'Rollback the earlier v3 population before targeting another menu.');
    }
    $items = wp_get_nav_menu_items($menu_id, array('post_status' => 'any'));
    $items = is_array($items) ? $items : array();
    $summary = array('added' => 0, 'existing' => 0, 'skipped' => 0, 'conflicts' => 0);
    if ($commit && empty($snapshot['menu_id'])) {
        $snapshot = array(
            'menu_id' => (int) $menu_id,
            'original_name' => (string) $menu->name,
            'created_at' => gmdate('c'),
            'added_ids' => array(),
            'renamed' => false,
        );
        update_option('ogmm_v3_snapshot', $snapshot, false);
    }
    $virtual_id = -1000;
    $get_or_create = static function ($parent, $label, $url, $classes) use (
        &$items, &$summary, &$snapshot, $menu_id, $commit, &$virtual_id
    ) {
        $existing = ogmm_v3_find_item($items, $parent, $label);
        if ($existing) {
            if ($url !== '#' && untrailingslashit((string) $existing->url) !== untrailingslashit((string) $url)) {
                $summary['conflicts']++;
            } else {
                $summary['existing']++;
            }
            return (int) $existing->ID;
        }
        $summary['added']++;
        if ($commit) {
            return ogmm_v3_menu_insert($menu_id, $parent, $label, $url, $classes, $snapshot, $items);
        }
        $id = $virtual_id--;
        $items[] = (object) array('ID' => $id, 'menu_item_parent' => (string) $parent, 'title' => $label, 'url' => $url);
        return $id;
    };
    $roots = array();
    // Seven headings are structurally stable even when a managed page is not ready.
    foreach (ogmm_v3_areas() as $area) {
        $root_url = '#';
        if ($area === 'MEDIA CENTER') {
            $media_page = get_page_by_path('media-center', OBJECT, 'page');
            if ($media_page instanceof WP_Post && $media_page->post_status === 'publish') {
                $root_url = home_url('/media-center/');
            }
        }
        $id = $get_or_create(0, $area, $root_url, array('onegodian-mega-root', 'ogmm-v3-root'));
        if (is_wp_error($id)) {
            return $id;
        }
        $roots[$area] = $id;
    }
    foreach ($manifest['entries'] as $entry) {
        if (($entry['reported_status'] ?? '') === 'Missing') {
            $summary['skipped']++;
            continue;
        }
        $page = get_page_by_path(trim($entry['slug'], '/'), OBJECT, 'page');
        preg_match('/^\[([a-z0-9_]+)\]$/', (string) $entry['shortcode'], $shortcode);
        if (!($page instanceof WP_Post) || $page->post_status !== 'publish'
            || empty($shortcode[1]) || !shortcode_exists($shortcode[1])) {
            $summary['skipped']++;
            continue;
        }
        $root_id = $roots[$entry['area']];
        $url = home_url($entry['slug']);
        if (ogmm_v3_has_url_in_area($items, $root_id, $url)) {
            $summary['existing']++;
            continue;
        }
        $group_id = $get_or_create($root_id, (string) $entry['group'], '#', array('onegodian-mega-group', 'ogmm-v3-column'));
        if (is_wp_error($group_id)) {
            return $group_id;
        }
        // If a pre-existing link has a matching title but stale URL, preserve it and report a conflict.
        $same_title = ogmm_v3_find_item($items, $group_id, $entry['label']);
        if ($same_title && untrailingslashit((string) $same_title->url) !== untrailingslashit((string) $url)) {
            $summary['conflicts']++;
            continue;
        }
        $classes = array('onegodian-managed-link', 'ogmm-v3-link');
        if (!empty($entry['login_required'])) {
            $classes[] = 'onegodian-login-required';
        }
        $id = $get_or_create($group_id, (string) $entry['label'], $url, $classes);
        if (is_wp_error($id)) {
            return $id;
        }
    }
    if ($commit && !$snapshot['renamed']
        && (stripos((string) $menu->name, 'OneGodian Mega Menu v2') !== false
            || stripos((string) $menu->name, '10.11.26 Mega Menu') !== false)) {
        $target_name = 'OneGodian Mega Menu v3';
        $named = wp_get_nav_menu_object($target_name);
        if (!$named || (int) $named->term_id === (int) $menu_id) {
            $result = wp_update_nav_menu_object($menu_id, array('menu-name' => $target_name));
            if (!is_wp_error($result)) {
                $snapshot['renamed'] = true;
                update_option('ogmm_v3_snapshot', $snapshot, false);
            }
        }
    }
    update_option('ogmm_v3_last_summary', $summary, false);
    return $summary;
}

function ogmm_v3_rollback() {
    $snapshot = get_option('ogmm_v3_snapshot', array());
    $menu_id = (int) ($snapshot['menu_id'] ?? 0);
    if (!$menu_id || !wp_get_nav_menu_object($menu_id)) {
        return new WP_Error('ogmm_missing_rollback', 'No valid v3 rollback snapshot is available.');
    }
    $deleted = 0;
    // Child items were created after their parents, so reverse order protects tree consistency.
    foreach (array_reverse($snapshot['added_ids'] ?? array()) as $id) {
        $id = (int) $id;
        if ($id > 0 && get_post_type($id) === 'nav_menu_item'
            && (int) get_post_meta($id, '_ogmm_v3_created_for_menu', true) === $menu_id) {
            if (function_exists('wp_delete_nav_menu_item')) {
                wp_delete_nav_menu_item($menu_id, $id);
            } else {
                wp_delete_post($id, true);
            }
            $deleted++;
        }
    }
    $menu = wp_get_nav_menu_object($menu_id);
    if (!empty($snapshot['renamed']) && $menu && $menu->name === 'OneGodian Mega Menu v3') {
        wp_update_nav_menu_object($menu_id, array('menu-name' => $snapshot['original_name']));
    }
    $settings = ogmm_v3_settings();
    $settings['enabled'] = false;
    update_option('ogmm_v3_settings', $settings, false);
    delete_option('ogmm_v3_snapshot');
    return $deleted;
}

function ogmm_v3_admin_menu() {
    add_theme_page('OneGodian Mega Menu v3', 'OneGodian Mega Menu v3', 'manage_options', 'ogmm-v3', 'ogmm_v3_admin_screen');
}
add_action('admin_menu', 'ogmm_v3_admin_menu');

function ogmm_v3_admin_screen() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Insufficient permissions.'));
    }
    $settings = ogmm_v3_settings();
    $selected_id = (int) $settings['menu_id'];
    $menus = wp_get_nav_menus();
    $snapshot = get_option('ogmm_v3_snapshot', array());
    $previous = get_option('ogmm_v3_last_summary', array());
    $notice = isset($_GET['ogmm_status']) ? sanitize_key(wp_unslash($_GET['ogmm_status'])) : '';
    $messages = array(
        'saved' => 'Configuration saved. Site navigation changes only when explicitly enabled.',
        'applied' => 'v3 population finished. Verify links, accessible behavior and theme layout on staging.',
        'rollback' => 'v3-created menu items removed, previous menu name restored, and v3 interception disabled.',
        'error' => 'Action failed. Check the configured menu, rollback snapshot and source page readiness.',
    );
    echo '<div class="wrap"><h1>OneGodian Mega Menu v3 <small>7 × 6 × 7</small></h1>';
    if (isset($messages[$notice])) {
        echo '<div class="notice ' . ($notice === 'error' ? 'notice-error' : 'notice-success') . '"><p>' . esc_html($messages[$notice]) . '</p></div>';
    }
    echo '<p>Reversible upgrade of the <strong>existing October 11, 2026 WordPress menu</strong>. Seven areas, at most six columns on the first desktop row and seven links per column. Existing item IDs, unrelated links, and menu-location assignments are retained.</p>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    wp_nonce_field('ogmm_v3_save');
    echo '<input type="hidden" name="action" value="ogmm_v3_save"/><table class="form-table"><tbody><tr><th><label for="ogmm-menu">Existing WordPress Menu</label></th><td><select required name="menu_id" id="ogmm-menu"><option value="">Select your current 10.11.26 mega menu</option>';
    foreach ($menus as $menu) {
        echo '<option value="' . (int) $menu->term_id . '" ' . selected($selected_id, (int) $menu->term_id, false) . '>' . esc_html($menu->name) . ' (#' . (int) $menu->term_id . ')</option>';
    }
    echo '</select></td></tr><tr><th>Frontend rendering</th><td><label><input type="checkbox" name="enabled" value="1" ' . checked(!empty($settings['enabled']), true, false) . '/> Replace only this selected menu’s frontend HTML with the v3 experience</label>';
    echo '<p class="description">Off by default. The source menu remains unchanged in WordPress. If LA-Studio Header Builder does not call wp_nav_menu(), place <code>[onegodian_mega_menu_v3]</code> in its shortcode element instead of enabling replacement. Do not use both rendering methods in the same header.</p></td></tr></tbody></table>';
    submit_button('Save v3 configuration');
    echo '</form><hr/>';
    echo '<h2>Deployment and rollback</h2><p>Back up the WordPress database first. Populate on staging; then explicitly enable v3. Publishing, shortcode validation and protected-page access controls are not changed by this plugin.</p>';
    if ($selected_id) {
        $summary = ogmm_v3_populate($selected_id, false);
        if (is_wp_error($summary)) {
            echo '<p><strong>' . esc_html($summary->get_error_message()) . '</strong></p>';
        } else {
            echo '<p>Dry-run estimate: <strong>' . (int) $summary['added'] . '</strong> items to add; <strong>' . (int) $summary['existing']
                . '</strong> existing; <strong>' . (int) $summary['skipped'] . '</strong> gated or missing; <strong>' . (int) $summary['conflicts'] . '</strong> title/URL conflicts. Preview is read-only.</p>';
        }
    }
    if (!empty($previous)) {
        echo '<p>Last population attempt: ' . esc_html(wp_json_encode($previous)) . '</p>';
    }
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    wp_nonce_field('ogmm_v3_apply');
    echo '<input type="hidden" name="action" value="ogmm_v3_apply"/>';
    submit_button('Populate existing menu (reversible)', 'primary', 'submit', false, array('onclick' => "return confirm('Database backup and staging review complete? This will ADD verified navigation items to your existing menu.');"));
    echo '</form> ';
    if (!empty($snapshot['menu_id'])) {
        echo '<p>Rollback snapshot: menu #' . (int) $snapshot['menu_id'] . ', '
            . count($snapshot['added_ids'] ?? array()) . ' v3-created items, original title: ' . esc_html((string) $snapshot['original_name']) . '.</p>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('ogmm_v3_rollback');
        echo '<input type="hidden" name="action" value="ogmm_v3_rollback"/>';
        submit_button('Rollback v3 menu additions and disable renderer', 'secondary', 'submit', false, array('onclick' => "return confirm('Remove ONLY items created by v3 and restore the prior menu title?');"));
        echo '</form>';
    }
    echo '<p><strong>Note:</strong> All 46 supplied routes remain subject to actual WordPress publication and registered-shortcode checks; Resources and Certificates need independent review. For reduced motion, accessibility testing and enterprise columns, see the bundled deployment guide.</p></div>';
}

function ogmm_v3_admin_redirect($status) {
    wp_safe_redirect(add_query_arg('ogmm_status', $status, admin_url('themes.php?page=ogmm-v3')));
    exit;
}

function ogmm_v3_admin_auth($action) {
    if (!current_user_can('manage_options')) {
        wp_die('Not authorized.', '', array('response' => 403));
    }
    check_admin_referer($action);
}
function ogmm_v3_handle_save() {
    ogmm_v3_admin_auth('ogmm_v3_save');
    $menu_id = isset($_POST['menu_id']) ? absint($_POST['menu_id']) : 0;
    if (!$menu_id || !wp_get_nav_menu_object($menu_id)) {
        ogmm_v3_admin_redirect('error');
    }
    $snapshot = get_option('ogmm_v3_snapshot', array());
    if (!empty($snapshot['menu_id']) && (int) $snapshot['menu_id'] !== $menu_id) {
        ogmm_v3_admin_redirect('error');
    }
    update_option('ogmm_v3_settings', array(
        'menu_id' => $menu_id,
        'enabled' => !empty($_POST['enabled']),
    ), false);
    ogmm_v3_admin_redirect('saved');
}
function ogmm_v3_handle_apply() {
    ogmm_v3_admin_auth('ogmm_v3_apply');
    $settings = ogmm_v3_settings();
    $result = ogmm_v3_populate((int) $settings['menu_id'], true);
    ogmm_v3_admin_redirect(is_wp_error($result) ? 'error' : 'applied');
}
function ogmm_v3_handle_rollback() {
    ogmm_v3_admin_auth('ogmm_v3_rollback');
    $result = ogmm_v3_rollback();
    ogmm_v3_admin_redirect(is_wp_error($result) ? 'error' : 'rollback');
}
add_action('admin_post_ogmm_v3_save', 'ogmm_v3_handle_save');
add_action('admin_post_ogmm_v3_apply', 'ogmm_v3_handle_apply');
add_action('admin_post_ogmm_v3_rollback', 'ogmm_v3_handle_rollback');
