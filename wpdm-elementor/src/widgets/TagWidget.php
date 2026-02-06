<?php

namespace WPDM\Elementor\Widgets;

/**
 * Tag Widget.
 * Displays packages filtered by tags.
 * Note: This widget is currently disabled in Main.php
 */
class TagWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = [
        'tagid', 'title', 'desc', 'items_per_page',
        'orderby', 'order', 'template',
        'cols', 'colspad', 'colsphone', 'toolbar'
    ];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'title' => 'text',
        'desc' => 'text',
        'items_per_page' => 'int',
        'orderby' => 'orderby',
        'order' => 'order',
        'template' => 'text',
        'cols' => 'int',
        'colspad' => 'int',
        'colsphone' => 'int',
        'toolbar' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmtag';
    }

    public function get_title()
    {
        return __('Packages By Tags', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-tags';
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
            'tagid',
            [
                'label' => __('Tags', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->getTagOptions(),
                'default' => []
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => __('Use "1" to show tag title, or enter custom text', WPDM_ELEMENTOR),
                'default' => '1'
            ]
        );

        $this->add_control(
            'desc',
            [
                'label' => __('Description', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => __('Use "1" to show tag description, or enter custom text', WPDM_ELEMENTOR),
                'default' => '1'
            ]
        );

        $this->add_control(
            'items_per_page',
            [
                'label' => __('Items Per Page', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'default' => 10
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => ['date' => __('Date', WPDM_ELEMENTOR), 'title' => __('Title', WPDM_ELEMENTOR)],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'ASC' => ['title' => __('Ascending', WPDM_ELEMENTOR), 'icon' => 'eicon-arrow-up'],
                    'DESC' => ['title' => __('Descending', WPDM_ELEMENTOR), 'icon' => 'eicon-arrow-down']
                ],
                'default' => 'DESC',
                'toggle' => false
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
            'cols',
            [
                'label' => __('Columns (Desktop)', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3
            ]
        );

        $this->add_control(
            'colspad',
            [
                'label' => __('Columns (Tablet)', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 4,
                'default' => 2
            ]
        );

        $this->add_control(
            'colsphone',
            [
                'label' => __('Columns (Phone)', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'default' => 1
            ]
        );

        $this->add_control(
            'toolbar',
            [
                'label' => __('Show Toolbar', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '1' => ['title' => __('Show', WPDM_ELEMENTOR), 'icon' => 'eicon-check'],
                    '0' => ['title' => __('Hide', WPDM_ELEMENTOR), 'icon' => 'eicon-close']
                ],
                'default' => '1',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        if (empty($settings['tagid'])) {
            return;
        }

        // Convert tagid array to comma-separated id
        $settings['id'] = is_array($settings['tagid'])
            ? implode(',', array_map('sanitize_text_field', $settings['tagid']))
            : sanitize_text_field($settings['tagid']);

        unset($settings['tagid']);

        echo WPDM()->package->shortCodes->packagesByTag($settings);
    }
}
