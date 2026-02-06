<?php
/**
 * Base widget class for all WPDM Elementor widgets.
 *
 * @package WPDM\Elementor\Widgets
 * @since   1.0.0
 */

namespace WPDM\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Abstract base class for all WPDM Elementor widgets.
 *
 * Provides common functionality, helper methods, and reduces code duplication
 * across all WPDM widgets. All widgets should extend this class.
 *
 * @since   1.0.0
 * @package WPDM\Elementor\Widgets
 */
abstract class BaseWidget extends Widget_Base
{
    /**
     * Get widget categories.
     *
     * All WPDM widgets belong to the 'wpdm' category.
     *
     * @since  1.0.0
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return ['wpdm'];
    }

    /**
     * Get widget keywords for search.
     *
     * Override in child classes to add specific keywords.
     *
     * @since  1.3.0
     * @return array Widget keywords.
     */
    public function get_keywords(): array
    {
        return ['wpdm', 'download', 'manager'];
    }

    /**
     * Get clean settings by specified keys only.
     *
     * Replaces the fragile array_slice approach with explicit key extraction.
     * This ensures only expected settings are passed to WPDM shortcodes.
     *
     * @since  1.0.0
     * @param  array $keys Array of setting keys to extract.
     * @return array Filtered settings array.
     */
    protected function getCleanSettings(array $keys): array
    {
        $all_settings = $this->get_settings_for_display();
        $clean = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $all_settings)) {
                $clean[$key] = $all_settings[$key];
            }
        }

        return $clean;
    }

    /**
     * Convert array setting to comma-separated string.
     *
     * @since  1.0.0
     * @param  array  $settings Settings array.
     * @param  string $key      Key of the setting to convert.
     * @return array Modified settings array.
     */
    protected function arrayToComma(array $settings, string $key): array
    {
        if (isset($settings[$key]) && is_array($settings[$key])) {
            $settings[$key] = implode(',', array_map('sanitize_text_field', $settings[$key]));
        }
        return $settings;
    }

    /**
     * Convert multiple array settings to comma-separated strings.
     *
     * @since  1.0.0
     * @param  array $settings Settings array.
     * @param  array $keys     Keys to convert.
     * @return array Modified settings array.
     */
    protected function arraysToComma(array $settings, array $keys): array
    {
        foreach ($keys as $key) {
            $settings = $this->arrayToComma($settings, $key);
        }
        return $settings;
    }

    /**
     * Sanitize settings based on type definitions.
     *
     * Supported types:
     * - 'int': Integer value (absint)
     * - 'bool': Boolean value
     * - 'text': Sanitized text field
     * - 'url': Sanitized URL
     * - 'html': Allowed HTML (wp_kses_post)
     * - 'css_class': CSS class name
     * - 'css_style': Inline CSS styles
     * - 'slug': URL slug
     * - 'order': ASC or DESC
     * - 'orderby': date, title, modified, or rand
     *
     * @since  1.0.0
     * @param  array $settings   Settings array.
     * @param  array $sanitizers Associative array of key => type.
     * @return array Sanitized settings array.
     */
    protected function sanitizeSettings(array $settings, array $sanitizers): array
    {
        foreach ($sanitizers as $key => $type) {
            if (!isset($settings[$key])) {
                continue;
            }

            $value = $settings[$key];

            switch ($type) {
                case 'int':
                    $settings[$key] = absint($value);
                    break;

                case 'bool':
                    $settings[$key] = (bool) $value;
                    break;

                case 'text':
                    $settings[$key] = sanitize_text_field($value);
                    break;

                case 'url':
                    $settings[$key] = esc_url_raw($value);
                    break;

                case 'html':
                    $settings[$key] = wp_kses_post($value);
                    break;

                case 'css_class':
                    $settings[$key] = sanitize_html_class($value);
                    break;

                case 'css_style':
                    $settings[$key] = $this->sanitizeCssStyle($value);
                    break;

                case 'slug':
                    $settings[$key] = sanitize_title($value);
                    break;

                case 'order':
                    $settings[$key] = in_array(strtoupper($value), ['ASC', 'DESC'], true)
                        ? strtoupper($value)
                        : 'DESC';
                    break;

                case 'orderby':
                    $allowed = ['date', 'title', 'modified', 'rand', 'menu_order'];
                    $settings[$key] = in_array($value, $allowed, true) ? $value : 'date';
                    break;
            }
        }

        return $settings;
    }

    /**
     * Sanitize inline CSS style attribute.
     *
     * Removes potentially dangerous CSS functions and HTML tags.
     *
     * @since  1.0.0
     * @param  string $style Raw CSS style string.
     * @return string Sanitized CSS style.
     */
    protected function sanitizeCssStyle(string $style): string
    {
        // Remove any HTML tags
        $style = wp_strip_all_tags($style);

        // Remove potentially dangerous CSS functions
        $dangerous = ['expression', 'javascript:', 'behavior:', 'vbscript:', 'url('];
        foreach ($dangerous as $pattern) {
            if (stripos($style, $pattern) !== false) {
                return '';
            }
        }

        return esc_attr($style);
    }

    /**
     * Wrap output in a container div.
     *
     * @since  1.0.0
     * @param  string $content HTML content to wrap.
     * @param  string $class   Additional CSS class (optional).
     * @return string Wrapped content.
     */
    protected function wrapOutput(string $content, string $class = ''): string
    {
        $classes = 'wpdm-elementor-widget';
        if (!empty($class)) {
            $classes .= ' ' . sanitize_html_class($class);
        }

        return sprintf('<div class="%s">%s</div>', esc_attr($classes), $content);
    }

    /**
     * Convert boolean-like settings (0/1 strings) to actual booleans.
     *
     * @since  1.0.0
     * @param  array $settings Settings array.
     * @param  array $keys     Keys to convert.
     * @return array Modified settings array.
     */
    protected function toBooleans(array $settings, array $keys): array
    {
        foreach ($keys as $key) {
            if (isset($settings[$key])) {
                $settings[$key] = (bool) $settings[$key];
            }
        }
        return $settings;
    }

    /**
     * Helper to get WPDM category terms for controls.
     *
     * @since  1.0.0
     * @return array Category slug => name pairs.
     */
    protected function getCategoryOptions(): array
    {
        return get_wpdmcategory_terms();
    }

    /**
     * Helper to get link template options for controls.
     *
     * @since  1.0.0
     * @return array Template options.
     */
    protected function getLinkTemplateOptions(): array
    {
        return get_elementor_link_templates();
    }

    /**
     * Helper to get WPDM tag terms for controls.
     *
     * @since  1.0.0
     * @return array Tag slug => name pairs.
     */
    protected function getTagOptions(): array
    {
        if (function_exists('get_wpdm_tag_terms')) {
            return get_wpdm_tag_terms();
        }

        $tags = get_terms(['taxonomy' => 'wpdmtag', 'hide_empty' => false]);
        $options = [];

        if (is_array($tags) && !is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $options[$tag->slug] = $tag->name;
            }
        }

        return $options;
    }

    /**
     * Get the SELECT2 AJAX configuration for package search.
     *
     * @since  1.0.0
     * @return array SELECT2 options array.
     */
    protected function getPackageSearchConfig(): array
    {
        return [
            'placeholder'        => __('Type package title', WPDM_ELEMENTOR),
            'ajax'               => [
                'url'      => get_rest_url(null, 'wpdm-elementor/v1/search-packages'),
                'dataType' => 'json',
                'delay'    => 250,
            ],
            'minimumInputLength' => 2,
        ];
    }

    /**
     * Register container style controls section.
     *
     * Adds background, border, and shadow controls for the widget container.
     *
     * @since  1.3.0
     * @param  string $section_id   Section ID (default: 'style_container').
     * @param  string $section_label Section label.
     * @return void
     */
    protected function registerContainerStyleControls(
        string $section_id = 'style_container',
        string $section_label = ''
    ): void {
        if (empty($section_label)) {
            $section_label = __('Container Style', WPDM_ELEMENTOR);
        }

        $this->start_controls_section(
            $section_id,
            [
                'label' => $section_label,
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label'     => __('Background Color', WPDM_ELEMENTOR),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpdm-elementor-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => __('Padding', WPDM_ELEMENTOR),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wpdm-elementor-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label'      => __('Margin', WPDM_ELEMENTOR),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wpdm-elementor-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .wpdm-elementor-widget',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label'      => __('Border Radius', WPDM_ELEMENTOR),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wpdm-elementor-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .wpdm-elementor-widget',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register typography style controls section.
     *
     * Adds typography controls for title and content.
     *
     * @since  1.3.0
     * @param  string $selector CSS selector for the typography target.
     * @return void
     */
    protected function registerTypographyControls(string $selector = '.wpdm-elementor-widget'): void
    {
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', WPDM_ELEMENTOR),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Title Color', WPDM_ELEMENTOR),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $selector . ' h1, {{WRAPPER}} ' . $selector . ' h2, {{WRAPPER}} ' . $selector . ' h3, {{WRAPPER}} ' . $selector . ' h4, {{WRAPPER}} ' . $selector . ' h5, {{WRAPPER}} ' . $selector . ' .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Title Typography', WPDM_ELEMENTOR),
                'selector' => '{{WRAPPER}} ' . $selector . ' h1, {{WRAPPER}} ' . $selector . ' h2, {{WRAPPER}} ' . $selector . ' h3, {{WRAPPER}} ' . $selector . ' h4, {{WRAPPER}} ' . $selector . ' h5, {{WRAPPER}} ' . $selector . ' .title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Text Color', WPDM_ELEMENTOR),
                'type'      => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'label'    => __('Text Typography', WPDM_ELEMENTOR),
                'selector' => '{{WRAPPER}} ' . $selector,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label'     => __('Link Color', WPDM_ELEMENTOR),
                'type'      => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} ' . $selector . ' a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label'     => __('Link Hover Color', WPDM_ELEMENTOR),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $selector . ' a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Check if we are in Elementor editor mode.
     *
     * Useful for showing placeholder content in the editor.
     *
     * @since  1.3.0
     * @return bool True if in editor mode.
     */
    protected function isEditorMode(): bool
    {
        return \Elementor\Plugin::$instance->editor->is_edit_mode();
    }

    /**
     * Render placeholder for empty content in editor.
     *
     * @since  1.3.0
     * @param  string $message Message to display.
     * @return void
     */
    protected function renderEditorPlaceholder(string $message): void
    {
        if ($this->isEditorMode()) {
            printf(
                '<div class="wpdm-elementor-placeholder" style="padding: 20px; background: #f5f5f5; border: 1px dashed #ccc; text-align: center;">%s</div>',
                esc_html($message)
            );
        }
    }
}
