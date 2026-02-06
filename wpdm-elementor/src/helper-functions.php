<?php
/**
 * Helper functions for WPDM Elementor integration.
 *
 * @package WPDM\Elementor
 * @since   1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_wpdm_link_templates')) {
    /**
     * Get WPDM link templates for shortcode usage.
     *
     * Returns an array of available link templates with their display names.
     * Template file extensions (.php) are stripped from the names.
     *
     * @since  1.0.0
     * @return array Associative array of template_file => template_name pairs.
     */
    function get_wpdm_link_templates(): array
    {
        if (!function_exists('WPDM')) {
            return [];
        }

        $link_templates = WPDM()->packageTemplate->getTemplates('link');

        if (!is_array($link_templates)) {
            return [];
        }

        foreach ($link_templates as &$template) {
            $template = str_replace('.php', '', $template);
        }

        return $link_templates;
    }
}

if (!function_exists('get_wpdmcategory_terms')) {
    /**
     * Get WPDM category terms for Elementor controls.
     *
     * Returns an array of category terms formatted for use in
     * Elementor SELECT2 controls.
     *
     * @since  1.0.0
     * @return array Associative array of slug => name pairs.
     */
    function get_wpdmcategory_terms(): array
    {
        $terms = get_terms([
            'taxonomy'   => 'wpdmcategory',
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }

        return $options;
    }
}

if (!function_exists('get_elementor_link_templates')) {
    /**
     * Get WPDM link templates formatted for Elementor controls.
     *
     * Returns an array of available link templates formatted for
     * Elementor SELECT2 controls, with file extensions removed.
     *
     * @since  1.0.0
     * @return array Associative array of template_key => template_key pairs.
     */
    function get_elementor_link_templates(): array
    {
        if (!function_exists('WPDM')) {
            return [];
        }

        $link_templates = WPDM()->packageTemplate->getTemplates('link');

        if (!is_array($link_templates)) {
            return [];
        }

        $options = [];
        foreach ($link_templates as $key => $value) {
            // Remove file extension from key
            $clean_key = strrpos($key, '.') !== false
                ? substr($key, 0, strrpos($key, '.'))
                : $key;
            $options[$clean_key] = $clean_key;
        }

        return $options;
    }
}

if (!function_exists('get_wpdm_tag_terms')) {
    /**
     * Get WPDM tag terms for Elementor controls.
     *
     * Returns an array of tag terms formatted for use in
     * Elementor SELECT2 controls.
     *
     * @since  1.3.0
     * @return array Associative array of slug => name pairs.
     */
    function get_wpdm_tag_terms(): array
    {
        $terms = get_terms([
            'taxonomy'   => 'wpdmtag',
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }

        return $options;
    }
}
