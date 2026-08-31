<?php
/**
 * Plugin Name: WPDM - Elementor
 * Plugin URI: https://www.wpdownloadmanager.com/download/wpdm-elementor/
 * Description: Download Manger modules for Elementor
 * Version: 2.0.2
 * Author: WordPress Download Manager
 * Text Domain: wpdm-elementor
 * Author URI: https://www.wpdownloadmanager.com/
 * Elementor tested up to: 4.2
 * Elementor Pro tested up to: 4.2
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check for required dependencies
 */
function wpdm_elementor_check_dependencies() {
    $missing = [];

    if (!did_action('elementor/loaded')) {
        $missing[] = 'Elementor';
    }

    if (!function_exists('WPDM')) {
        $missing[] = 'WordPress Download Manager';
    }

    return $missing;
}

/**
 * Display admin notice for missing dependencies
 */
function wpdm_elementor_missing_dependencies_notice() {
    $missing = wpdm_elementor_check_dependencies();
    if (empty($missing)) {
        return;
    }

    $message = sprintf(
        '<strong>WPDM - Elementor</strong> requires %s to be installed and activated.',
        implode(' and ', $missing)
    );

    printf('<div class="notice notice-error"><p>%s</p></div>', $message);
}
add_action('admin_notices', 'wpdm_elementor_missing_dependencies_notice');

/**
 * Initialize plugin only if dependencies are met
 */
function wpdm_elementor_init() {
    $missing = wpdm_elementor_check_dependencies();
    if (!empty($missing)) {
        return;
    }

    define("__WPDM_ELEMENTOR__", true);

    // Load constants first
    require_once __DIR__.'/src/constants.php';

    require_once __DIR__.'/src/api/API.php';
    require_once __DIR__.'/src/Main.php';

    \WPDM\Elementor\Main::getInstance();
}
add_action('plugins_loaded', 'wpdm_elementor_init');
