<?php

namespace WPDM\Elementor\Widgets;

/**
 * Category Widget.
 * Displays packages filtered by category.
 */
class CategoryWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = [
        'catid', 'operator', 'title', 'desc', 'items_per_page',
        'orderby', 'order', 'template', 'author',
        'cols', 'colspad', 'colsphone', 'toolbar', 'paging'
    ];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'operator' => 'text',
        'title' => 'text',
        'desc' => 'text',
        'items_per_page' => 'int',
        'orderby' => 'orderby',
        'order' => 'order',
        'template' => 'text',
        'author' => 'text',
        'cols' => 'int',
        'colspad' => 'int',
        'colsphone' => 'int',
        'toolbar' => 'text',
        'paging' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmcategory';
    }

    public function get_title()
    {
        return __('Packages By Category', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-theme-builder';
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
            'catid',
            [
                'label' => __('Include Categories', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->getCategoryOptions(),
                'default' => []
            ]
        );

        $this->add_control(
            'operator',
            [
                'label' => __('Operator', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => [
                    'IN' => 'IN',
                    'NOT IN' => 'NOT IN',
                    'AND' => 'AND',
                    'EXISTS' => 'EXISTS',
                    'NOT EXISTS' => 'NOT EXISTS'
                ],
                'default' => 'IN',
                'description' => __('Use this parameter only when using multiple categories.', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => __('Use "1" to show category title, or enter custom text', WPDM_ELEMENTOR),
                'default' => '1'
            ]
        );

        $this->add_control(
            'desc',
            [
                'label' => __('Description', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => __('Use "1" to show category description, or enter custom text', WPDM_ELEMENTOR),
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
                'options' => $this->getLinkTemplateOptions(),
                'default' => 'link-template-default'
            ]
        );

        $this->add_control(
            'author',
            [
                'label' => __('Authors', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('e.g: 1, 2, 3', WPDM_ELEMENTOR),
                'description' => __('Author IDs separated by comma', WPDM_ELEMENTOR)
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

        $this->add_control(
            'paging',
            [
                'label' => __('Pagination', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '1' => ['title' => __('Show', WPDM_ELEMENTOR), 'icon' => 'eicon-check'],
                    '0' => ['title' => __('Hide', WPDM_ELEMENTOR), 'icon' => 'eicon-close']
                ],
                'default' => '0',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        if (empty($settings['catid'])) {
            return;
        }

        // Convert catid array to comma-separated id
        $settings['id'] = is_array($settings['catid'])
            ? implode(',', array_map('sanitize_text_field', $settings['catid']))
            : sanitize_text_field($settings['catid']);

        unset($settings['catid']);

        echo WPDM()->categories->shortcode->listPackages($settings);
    }
}
