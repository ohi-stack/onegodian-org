<?php
/**
 * Plugin Name: OneGodian 2027 Navigation
 * Description: Accessible seven-section OneGodian.org mega menu and mobile accordion from one canonical hierarchy.
 * Version: 0.1.0
 * Requires PHP: 7.4
 * Author: ONEGODIAN, LLC
 */
if (!defined('ABSPATH')) { exit; }

function og27_nav_data() {
    static $data = null;
    if ($data !== null) { return $data; }
    $file = plugin_dir_path(__FILE__) . 'menu.json';
    $decoded = is_readable($file) ? json_decode((string) file_get_contents($file), true) : null;
    $data = is_array($decoded) && !empty($decoded['items']) ? $decoded : array('items' => array());
    return $data;
}

function og27_nav_target($path, $external = false) {
    if ($external) {
        return preg_match('#^https://#', (string) $path) ? esc_url($path) : '';
    }
    if (!is_string($path) || !preg_match('#^/[a-zA-Z0-9/_-]*/$#', $path)) {
        return '';
    }
    if ($path === '/') { return esc_url(home_url('/')); }
    $page = get_page_by_path(trim($path, '/'), OBJECT, 'page');
    if (!$page || $page->post_status !== 'publish') { return ''; }
    return esc_url(get_permalink($page));
}

function og27_nav_link($link) {
    $name = isset($link['name']) ? (string) $link['name'] : '';
    $path = isset($link['path']) ? (string) $link['path'] : '';
    $href = og27_nav_target($path, !empty($link['external']));
    $class = !empty($link['featured']) ? 'og27-link og27-link-featured' : 'og27-link';
    if (!$href) {
        return '<span class="' . esc_attr($class . ' og27-pending') .
            '" title="' . esc_attr__('This destination is pending publication or access verification.', 'onegodian-2027-nav') .
            '">' . esc_html($name) . ' <small>' . esc_html__('Pending', 'onegodian-2027-nav') . '</small></span>';
    }
    return '<a class="' . esc_attr($class) . '" href="' . $href . '">' . esc_html($name) .
        (!empty($link['login']) ? ' <span class="og27-lock" aria-label="' . esc_attr__('Members only', 'onegodian-2027-nav') . '">◦</span>' : '') .
        '</a>';
}

function og27_render_navigation() {
    $data = og27_nav_data();
    if (empty($data['items'])) { return ''; }
    $logged_in = is_user_logged_in();
    ob_start();
    ?>
    <nav class="og27-nav" aria-label="<?php echo esc_attr__('OneGodian 2027 primary navigation', 'onegodian-2027-nav'); ?>">
      <a class="og27-brand" href="<?php echo esc_url(home_url('/')); ?>">ONEGODIAN<span>™</span></a>
      <details class="og27-shell">
        <summary class="og27-mobile-toggle"><?php echo esc_html__('Explore Menu', 'onegodian-2027-nav'); ?> <span aria-hidden="true">☰</span></summary>
        <div class="og27-menu">
          <?php foreach ($data['items'] as $item) :
              $title = isset($item['name']) ? (string) $item['name'] : '';
              $landing = og27_nav_target(isset($item['path']) ? $item['path'] : '');
              ?>
              <details class="og27-item">
                <summary><?php echo esc_html($title); ?><span class="og27-chevron" aria-hidden="true">⌄</span></summary>
                <div class="og27-panel">
                  <div class="og27-panel-heading">
                    <strong><?php echo esc_html($title); ?></strong>
                    <?php if ($landing) : ?><a href="<?php echo $landing; ?>"><?php echo esc_html__('Explore all', 'onegodian-2027-nav'); ?> →</a><?php endif; ?>
                  </div>
                  <div class="og27-columns">
                    <?php foreach ((array) ($item['columns'] ?? array()) as $column) : ?>
                      <section class="og27-column">
                        <h2><?php echo esc_html((string) ($column['title'] ?? 'Explore')); ?></h2>
                        <?php foreach ((array) ($column['links'] ?? array()) as $link) : ?>
                          <?php echo og27_nav_link($link); // Escaped inside link renderer. ?>
                        <?php endforeach; ?>
                      </section>
                    <?php endforeach; ?>
                  </div>
                </div>
              </details>
          <?php endforeach; ?>
        </div>
      </details>
      <?php $account = og27_nav_target($logged_in ? '/dashboard/' : '/account/'); ?>
      <?php if ($account) : ?>
        <a class="og27-account" href="<?php echo $account; ?>"><?php echo esc_html($logged_in ? 'My Dashboard' : 'Login / My Dashboard'); ?></a>
      <?php else : ?>
        <span class="og27-account og27-pending"><?php echo esc_html($logged_in ? 'My Dashboard' : 'Login / My Dashboard'); ?></span>
      <?php endif; ?>
    </nav>
    <?php
    return (string) ob_get_clean();
}
add_shortcode('onegodian_2027_navigation', 'og27_render_navigation');
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('og27-navigation', plugins_url('assets/navigation.css', __FILE__), array(), '0.1.0');
    wp_enqueue_script('og27-navigation', plugins_url('assets/navigation.js', __FILE__), array(), '0.1.0', true);
});
