<?php
/**
 * Plugin constants for WPDM Elementor integration.
 *
 * @package WPDM\Elementor
 * @since   1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Text domain for translations.
 *
 * @var string
 */
if (!defined('WPDM_ELEMENTOR')) {
    define('WPDM_ELEMENTOR', 'wpdm-elementor');
}

/**
 * Plugin version.
 *
 * @var string
 */
if (!defined('WPDM_ELEMENTOR_VERSION')) {
    define('WPDM_ELEMENTOR_VERSION', '2.0.0');
}

/**
 * Plugin directory path.
 *
 * @var string
 */
if (!defined('WPDM_ELEMENTOR_PATH')) {
    define('WPDM_ELEMENTOR_PATH', dirname(__DIR__) . '/');
}

/**
 * Plugin directory URL.
 *
 * @var string
 */
if (!defined('WPDM_ELEMENTOR_URL')) {
    define('WPDM_ELEMENTOR_URL', plugin_dir_url(dirname(__FILE__)));
}
