<?php

namespace WPDM\Elementor\Widgets;

/**
 * Search Result Widget.
 * Displays search box and search results.
 */
class SearchResultWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['template', 'init', 'cols'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'template' => 'text',
        'init' => 'text',
        'cols' => 'int',
    ];

    public function get_name()
    {
        return 'wpdmsearchresult';
    }

    public function get_title()
    {
        return __('Search Result', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-search-results';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Parameters', WPDM_ELEMENTOR),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'template',
            [
                'label' => __('Link Template', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => get_wpdm_link_templates(),
                'default' => 'link-template-default'
            ]
        );

        $this->add_control(
            'init',
            [
                'label' => __('Show Initial Results', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '1',
                'description' => __('Show latest packages before search', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'cols',
            [
                'label' => __('Columns', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 4,
                'default' => 3
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        echo WPDM()->package->shortCodes->searchResult($settings);
    }
}
