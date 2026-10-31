<?php
/**
 * Deterministic WordPress API stub smoke test for OneGodian Mega Menu v3.
 * No database, HTTP requests, site mutation or WordPress installation required.
 */
error_reporting(E_ALL);
define('ABSPATH', '/fake-wordpress/');

$GLOBALS['ogmm_opts'] = array(
    'ogmm_v3_settings' => array('menu_id' => 101, 'enabled' => false),
);
$GLOBALS['ogmm_menu'] = (object) array('term_id' => 101, 'name' => 'OneGodian Mega Menu v2');
$GLOBALS['ogmm_items'] = array(
    (object) array('ID' => 200, 'menu_item_parent' => '0', 'title' => 'START HERE', 'url' => 'https://onegodian.org/start-here/', 'target' => ''),
    (object) array('ID' => 201, 'menu_item_parent' => '0', 'title' => 'RESOURCES', 'url' => '#', 'target' => ''),
    (object) array('ID' => 202, 'menu_item_parent' => '201', 'title' => 'External Guide', 'url' => 'https://onegodian.org/guide/', 'target' => ''),
    (object) array('ID' => 203, 'menu_item_parent' => '0', 'title' => 'TIME', 'url' => 'https://onegodian.org/onegodian-time/', 'target' => ''),
    (object) array('ID' => 204, 'menu_item_parent' => '203', 'title' => 'Legacy Calendar', 'url' => 'https://onegodian.org/sciences/time/', 'target' => ''),
);
$GLOBALS['ogmm_meta'] = array();
$GLOBALS['ogmm_last_id'] = 204;
$GLOBALS['ogmm_shortcode'] = array();

function plugin_dir_path($file) { return dirname($file) . '/'; }
function plugin_dir_url($file) { return 'https://onegodian.org/wp-content/plugins/onegodian-mega-menu-v3/'; }
function add_action() {}
function add_filter() {}
function add_shortcode() {}
function wp_parse_args($original, $defaults) { return array_merge($defaults, $original); }
function get_option($name, $default = false) { return $GLOBALS['ogmm_opts'][$name] ?? $default; }
function update_option($name, $value, $autoload = null) { $GLOBALS['ogmm_opts'][$name] = $value; return true; }
function delete_option($name) { unset($GLOBALS['ogmm_opts'][$name]); }
function wp_get_nav_menu_object($id) {
    $menu = $GLOBALS['ogmm_menu'];
    if ($id === 101 || $id === '101' || $id === $menu->name) { return $menu; }
    return false;
}
function wp_get_nav_menu_items($id, $opts = array()) { return $GLOBALS['ogmm_items']; }
function wp_update_nav_menu_item($menu_id, $item_id, $args) {
    if ($menu_id !== 101) throw new Exception('Wrong menu modified');
    $id = ++$GLOBALS['ogmm_last_id'];
    $GLOBALS['ogmm_items'][] = (object) array(
        'ID' => $id,
        'menu_item_parent' => (string) $args['menu-item-parent-id'],
        'title' => $args['menu-item-title'],
        'url' => $args['menu-item-url'],
        'target' => '',
    );
    return $id;
}
function wp_update_nav_menu_object($id, $args) {
    $GLOBALS['ogmm_menu']->name = $args['menu-name'];
    return $id;
}
function update_post_meta($id, $key, $value) { $GLOBALS['ogmm_meta'][$id][$key] = $value; }
function get_post_meta($id, $key, $single = true) { return $GLOBALS['ogmm_meta'][$id][$key] ?? ''; }
function get_post_type($id) { foreach ($GLOBALS['ogmm_items'] as $i) if ($i->ID === $id) return 'nav_menu_item'; return false; }
function wp_delete_nav_menu_item($menu_id, $id) {
    $GLOBALS['ogmm_items'] = array_values(array_filter($GLOBALS['ogmm_items'], static function ($item) use ($id) { return $item->ID !== $id; }));
    unset($GLOBALS['ogmm_meta'][$id]);
    return true;
}
function home_url($slug) { return 'https://onegodian.org' . $slug; }
function untrailingslashit($s) { return rtrim($s, '/'); }
function is_wp_error($obj) { return $obj instanceof WP_Error; }
function shortcode_exists($shortcode) { return true; }
function get_page_by_path($path, $output = null, $type = null) {
    $page = new WP_Post();
    $page->post_status = 'publish';
    return $page;
}
function esc_attr($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
function esc_html($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
function esc_url($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
function wp_parse_url($url, $part = -1) { return parse_url($url, $part); }
function is_admin() { return false; }
function wp_doing_ajax() { return false; }
function get_nav_menu_locations() { return array('primary' => 101); }
class WP_Error {
    private $message;
    public function __construct($code, $message) { $this->message = $message; }
    public function get_error_message() { return $this->message; }
}
class WP_Post {}
define('OBJECT', 'OBJECT');

require dirname(__DIR__) . '/wordpress/plugins/onegodian-mega-menu-v3/onegodian-mega-menu-v3.php';
function require_test($condition, $message) { if (!$condition) { throw new Exception($message); } }

$manifest = ogmm_v3_manifest();
require_test(!is_wp_error($manifest) && count($manifest['entries']) === 46, 'Invalid embedded manifest');
$before = count($GLOBALS['ogmm_items']);
$preview = ogmm_v3_populate(101, false);
require_test(!is_wp_error($preview), 'Preview failed');
require_test(count($GLOBALS['ogmm_items']) === $before, 'Dry-run changed the menu');
require_test(!get_option('ogmm_v3_snapshot'), 'Dry-run created rollback snapshot');
require_test($preview['skipped'] === 0, 'Staged test fixture should register all 46 shortcodes');

$applied = ogmm_v3_populate(101, true);
require_test(!is_wp_error($applied), 'Apply failed: ' . (is_wp_error($applied) ? $applied->get_error_message() : ''));
require_test($applied['skipped'] === 0, 'Simulated ready pages unexpectedly skipped');
require_test($GLOBALS['ogmm_menu']->name === 'OneGodian Mega Menu v3', 'Menu v2 not renamed to v3');
require_test(count($GLOBALS['ogmm_items']) > $before, 'No verified menu items created');
$snapshot = get_option('ogmm_v3_snapshot');
require_test((int) $snapshot['menu_id'] === 101, 'No rollback ledger recorded');
require_test(count($snapshot['added_ids']) === $applied['added'], 'Ledger does not match inserted menu items');

$rendered = ogmm_v3_render(101);
require_test(substr_count($rendered, 'class="ogmm-v3__item"') === 7, 'Seven canonical root sections missing');
require_test(substr_count($rendered, 'class="ogmm-v3__column"') <= 42, 'Seven by six column bound exceeded');
require_test(strpos($rendered, 'href="https://onegodian.org/dashboard/"') !== false, 'Member Dashboard missing');
require_test(strpos($rendered, 'aria-label="Sign-in required"') !== false, 'Member label missing');
require_test(strpos($rendered, 'https://onegodian.org/members/') !== false, 'Approved Members route missing');
require_test(strpos($rendered, 'https://onegodian.org/groups/') !== false, 'Approved Groups route missing');
require_test(strpos($rendered, 'https://onegodian.org/onegodian-time/') !== false, 'OneGodian Time missing under Learn');
require_test(strpos($rendered, 'ogmm-v3-1-panel-media-center') !== false, 'Media Center panel ID invalid or missing');
require_test(strpos($rendered, 'ogmm-v3__utility">TIME') === false, 'Legacy TIME displayed as an eighth root');
require_test(substr_count($rendered, 'class="ogmm-v3__item"') === 7, 'Navigation must contain exactly seven primary sections');
require_test(strpos($rendered, 'https://onegodian.org/start-here/') !== false, 'Existing unrelated utility root removed');
require_test(strpos($rendered, 'https://onegodian.org/guide/') !== false, 'Existing nested utility link removed');

$repeat = ogmm_v3_populate(101, true);
require_test(!is_wp_error($repeat) && $repeat['added'] === 0, 'Population is not idempotent');

$settings = ogmm_v3_settings();
$settings['enabled'] = true;
update_option('ogmm_v3_settings', $settings);
require_test(ogmm_v3_replace_selected_menu('native-nav', (object) array('menu' => 102)) === 'native-nav', 'Unselected menu intercepted');
require_test(strpos(ogmm_v3_replace_selected_menu('native-nav', (object) array('theme_location' => 'primary')), 'ogmm-v3') !== false, 'Assigned menu not enhanced');

$rolled_back = ogmm_v3_rollback();
require_test(!is_wp_error($rolled_back) && $rolled_back === $applied['added'], 'Rollback did not delete only created IDs');
require_test($GLOBALS['ogmm_menu']->name === 'OneGodian Mega Menu v2', 'Rollback did not restore original menu title');
require_test(count($GLOBALS['ogmm_items']) === $before, 'Rollback failed to preserve exact pre-apply menu');
require_test(!ogmm_v3_settings()['enabled'], 'Rollback did not disable enhanced renderer');
require_test(!get_option('ogmm_v3_snapshot'), 'Rollback ledger not cleared');

echo "PASS: WordPress stub v2→v3.1 apply, 46 managed routes, seven roots, nested Time, Media Center, protected pages, idempotency and rollback.\n";
