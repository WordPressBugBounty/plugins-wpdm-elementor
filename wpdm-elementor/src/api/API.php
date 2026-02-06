<?php
/**
 * REST API endpoints for WPDM Elementor integration.
 *
 * @package WPDM\Elementor\API
 * @since   1.0.0
 */

namespace WPDM\Elementor\API;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Class API
 *
 * Handles REST API endpoints for the WPDM Elementor integration.
 * Provides package search functionality for SELECT2 dropdowns.
 *
 * @since 1.0.0
 */
class API
{
    /**
     * REST API namespace.
     *
     * @var string
     */
    const API_NAMESPACE = 'wpdm-elementor/v1';

    /**
     * Get the singleton instance.
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
     * Private constructor.
     *
     * Registers the REST API initialization hook.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        // Check if rest_api_init already fired
        if (did_action('rest_api_init')) {
            $this->registerAPIEndpoints();
        } else {
            add_action('rest_api_init', [$this, 'registerAPIEndpoints']);
        }
    }

    /**
     * Register REST API endpoints.
     *
     * @since 1.0.0
     * @return void
     */
    public function registerAPIEndpoints(): void
    {
        register_rest_route(
            self::API_NAMESPACE,
            '/search-packages',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'searchPackages'],
                'permission_callback' => '__return_true',
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            '/search-categories',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'searchCategories'],
                'permission_callback' => '__return_true',
            ]
        );
    }

    /**
     * Check if the current user has permission to access the endpoint.
     *
     * Returns true as this is a read-only search endpoint
     * used in Elementor editor context.
     *
     * @since  1.0.0
     * @return bool Always returns true.
     */
    public function checkPermission(): bool
    {
        return true;
    }

    /**
     * Search packages by title.
     *
     * Returns packages matching the search term for SELECT2 dropdown.
     *
     * @since  1.0.0
     * @param  WP_REST_Request $request REST request object.
     * @return WP_REST_Response JSON response with package results.
     */
    public function searchPackages(WP_REST_Request $request): WP_REST_Response
    {
        global $wpdb;

        $term = $request->get_param('term');
        $packages = [];

        if (!empty($term)) {
            // Use prepared statement for security
            $like_term = '%' . $wpdb->esc_like($term) . '%';

            $results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT ID, post_title
                     FROM {$wpdb->posts}
                     WHERE post_type = 'wpdmpro'
                     AND post_status = 'publish'
                     AND post_title LIKE %s
                     ORDER BY post_title ASC
                     LIMIT 20",
                    $like_term
                )
            );

            if ($results) {
                foreach ($results as $row) {
                    $packages[] = [
                        'id'   => (int) $row->ID,
                        'text' => esc_html($row->post_title),
                    ];
                }
            }
        }

        // Results key is required for jQuery SELECT2
        return new WP_REST_Response(['results' => $packages], 200);
    }

    /**
     * Search categories by name.
     *
     * Returns categories matching the search term for SELECT2 dropdown.
     *
     * @since  1.3.0
     * @param  WP_REST_Request $request REST request object.
     * @return WP_REST_Response JSON response with category results.
     */
    public function searchCategories(WP_REST_Request $request): WP_REST_Response
    {
        $term = $request->get_param('term');
        $categories = [];

        $args = [
            'taxonomy'   => 'wpdmcategory',
            'hide_empty' => false,
            'number'     => 20,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ];

        if (!empty($term)) {
            $args['search'] = $term;
        }

        $terms = get_terms($args);

        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $category) {
                $categories[] = [
                    'id'   => $category->slug,
                    'text' => esc_html($category->name),
                ];
            }
        }

        return new WP_REST_Response(['results' => $categories], 200);
    }
}
