<?php

/**
 * File includes for WPDM Elementor plugin.
 *
 * If Composer autoload is available, use it.
 * Otherwise, fall back to manual includes.
 */

$composer_autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
} else {
    // Manual includes (fallback when Composer is not used)
    include_once dirname(__DIR__) . '/src/constants.php';
    include_once dirname(__DIR__) . '/src/helper-functions.php';

    // Base widget class (must be loaded before other widgets)
    include_once dirname(__DIR__) . '/src/widgets/BaseWidget.php';

    // Widget classes
    include_once dirname(__DIR__) . '/src/widgets/AllPackagesWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/CategoryWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/DirectLinkWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/FrontendWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/LoginFormWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/PackagesWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/PackageWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/RegFormWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/SearchResultWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/TagWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/UserDashboardWidget.php';
    include_once dirname(__DIR__) . '/src/widgets/UserProfileWidget.php';
}










