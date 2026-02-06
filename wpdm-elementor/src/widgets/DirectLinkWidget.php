<?php

namespace WPDM\Elementor\Widgets;

/**
 * Direct Link Widget.
 * Creates a direct download link for a package.
 */
class DirectLinkWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['pid', 'target', 'label', 'class', 'eid', 'style'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'pid' => 'int',
        'target' => 'text',
        'label' => 'text',
        'class' => 'css_class',
        'eid' => 'text',
        'style' => 'css_style',
    ];

    public function get_name()
    {
        return 'wpdmdirectlink';
    }

    public function get_title()
    {
        return __('Direct download link', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-editor-link';
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
            'pid',
            [
                'label' => __('Package', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'placeholder' => __('Package', WPDM_ELEMENTOR),
                'select2options' => $this->getPackageSearchConfig()
            ]
        );

        $this->add_control(
            'target',
            [
                'label' => __('Link Target', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => ['_blank' => '_blank', '_self' => '_self'],
                'default' => '_blank'
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => __('Download link label', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Download',
                'placeholder' => __('Download', WPDM_ELEMENTOR),
            ]
        );

        $this->add_control(
            'class',
            [
                'label' => __('CSS class name', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
            ]
        );

        $this->add_control(
            'eid',
            [
                'label' => __('HTML element ID', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => __('CSS Style', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'placeholder' => __('e.g., color: #3399ff;', WPDM_ELEMENTOR),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        if (empty($settings['pid'])) {
            return;
        }

        // Map pid to id for WPDM shortcode
        $settings['id'] = $settings['pid'];
        unset($settings['pid']);

        echo $this->wrapOutput(
            WPDM()->package->shortCodes->directLink($settings),
            'direct-link-widget'
        );
    }
}
