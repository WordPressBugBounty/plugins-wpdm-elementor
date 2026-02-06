<?php
/**
 * Main plugin class for WPDM Elementor integration.
 *
 * @package WPDM\Elementor
 * @since   1.0.0
 */

namespace WPDM\Elementor;

use Elementor\Elements_Manager;
use Elementor\Widgets_Manager;
use WPDM\Elementor\API\API;
use WPDM\Elementor\Widgets\AllPackagesWidget;
use WPDM\Elementor\Widgets\CategoryWidget;
use WPDM\Elementor\Widgets\DirectLinkWidget;
use WPDM\Elementor\Widgets\FrontendWidget;
use WPDM\Elementor\Widgets\LoginFormWidget;
use WPDM\Elementor\Widgets\PackagesWidget;
use WPDM\Elementor\Widgets\PackageWidget;
use WPDM\Elementor\Widgets\RegFormWidget;
use WPDM\Elementor\Widgets\SearchResultWidget;
use WPDM\Elementor\Widgets\TagWidget;
use WPDM\Elementor\Widgets\UserDashboardWidget;
use WPDM\Elementor\Widgets\UserProfileWidget;

/**
 * Class Main
 *
 * Handles the initialization and registration of WPDM widgets with Elementor.
 * Implements the Singleton pattern to ensure only one instance exists.
 *
 * @since 1.0.0
 */
final class Main
{
    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '2.0.0';

    /**
     * Minimum Elementor version required.
     *
     * @var string
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP version required.
     *
     * @var string
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Get the singleton instance.
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since  1.0.0
     * @return self The singleton instance.
     */
    public static function getInstance(): self
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Private constructor to prevent direct instantiation.
     *
     * Initializes the API and sets up WordPress hooks.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        API::getInstance();

        // Load text domain
        load_plugin_textdomain(
            'wpdm-elementor',
            false,
            dirname(plugin_basename(__DIR__)) . '/languages/'
        );

        // Register Elementor hooks - check if elementor/init already fired
        if (did_action('elementor/init')) {
            $this->addHooks();
        } else {
            add_action('elementor/init', [$this, 'addHooks']);
        }
    }

    /**
     * Register Elementor-specific hooks.
     *
     * Sets up category and widget registration hooks.
     *
     * @since 1.0.0
     * @return void
     */
    public function addHooks(): void
    {
        add_action('elementor/elements/categories_registered', [$this, 'registerCategory'], 0);
        add_action('elementor/widgets/register', [$this, 'registerWidgets'], 99);
    }

    /**
     * Register the WPDM widget category.
     *
     * Adds a 'Download Manager' category to the Elementor widget panel.
     *
     * @since 1.0.0
     * @param Elements_Manager $elements_manager Elementor elements manager instance.
     * @return void
     */
    public function registerCategory(Elements_Manager $elements_manager): void
    {
        $elements_manager->add_category(
            'wpdm',
            [
                'title' => __('Download Manager', WPDM_ELEMENTOR),
                'icon'  => 'eicon-download-button',
            ]
        );
    }

    /**
     * Register all WPDM widgets with Elementor.
     *
     * Includes the widget files and registers each widget class.
     *
     * @since 1.0.0
     * @param Widgets_Manager $widget_manager Elementor widgets manager instance.
     * @return void
     */
    public function registerWidgets(Widgets_Manager $widget_manager): void
    {
        require_once __DIR__ . '/includes.php';

        // Package-related widgets
        $widget_manager->register(new PackagesWidget());
        $widget_manager->register(new PackageWidget());
        $widget_manager->register(new CategoryWidget());
        $widget_manager->register(new AllPackagesWidget());
        $widget_manager->register(new SearchResultWidget());
        $widget_manager->register(new DirectLinkWidget());

        // User-related widgets
        $widget_manager->register(new RegFormWidget());
        $widget_manager->register(new LoginFormWidget());
        $widget_manager->register(new FrontendWidget());
        $widget_manager->register(new UserDashboardWidget());

        // Disabled widgets (uncomment to enable)
        // $widget_manager->register(new TagWidget());
        // $widget_manager->register(new UserProfileWidget());
    }

    /**
     * Get the plugin version.
     *
     * @since  1.3.0
     * @return string Plugin version.
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }
}
