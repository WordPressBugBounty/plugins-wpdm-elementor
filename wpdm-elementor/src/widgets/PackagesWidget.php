<?php

namespace WPDM\Elementor\Widgets;

/**
 * Packages Widget.
 * Displays multiple packages with filtering options.
 */
class PackagesWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = [
        'search', 'categories', 'include_children', 'tags', 'author',
        'orderby', 'order', 'items_per_page', 'template',
        'cols', 'colspad', 'colsphone', 'toolbar', 'paging', 'login', 'async'
    ];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'search' => 'text',
        'include_children' => 'text',
        'author' => 'text',
        'orderby' => 'orderby',
        'order' => 'order',
        'items_per_page' => 'int',
        'template' => 'text',
        'cols' => 'int',
        'colspad' => 'int',
        'colsphone' => 'int',
        'toolbar' => 'text',
        'paging' => 'text',
        'login' => 'text',
        'async' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmpackages';
    }

    public function get_title()
    {
        return __('Packages', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
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
            'search',
            [
                'label' => __('Search Keywords', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('search keywords', WPDM_ELEMENTOR),
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Include Categories', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->getCategoryOptions(),
                'default' => []
            ]
        );

        $this->add_control(
            'include_children',
            [
                'label' => __('Include Children', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '0'
            ]
        );

        $tag_options = $this->getTagOptions();
        if (!empty($tag_options)) {
            $this->add_control(
                'tags',
                [
                    'label' => __('Tags', WPDM_ELEMENTOR),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $tag_options,
                    'default' => []
                ]
            );
        }

        $this->add_control(
            'author',
            [
                'label' => __('Authors', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('1, 2, 3', WPDM_ELEMENTOR),
                'description' => __('Author IDs separated by comma', WPDM_ELEMENTOR)
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
            'template',
            [
                'label' => __('Link Template', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->getLinkTemplateOptions(),
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

        $this->add_control(
            'login',
            [
                'label' => __('Require Login', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '0'
            ]
        );

        $this->add_control(
            'async',
            [
                'label' => __('Async Loading', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '1' => ['title' => __('Enable', WPDM_ELEMENTOR), 'icon' => 'eicon-check'],
                    '0' => ['title' => __('Disable', WPDM_ELEMENTOR), 'icon' => 'eicon-close']
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

        // Convert arrays to comma-separated strings
        $settings = $this->arraysToComma($settings, ['categories', 'tags']);

        echo WPDM()->package->shortCodes->packages($settings);
    }
}
