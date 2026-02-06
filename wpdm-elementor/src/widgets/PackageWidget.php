<?php

namespace WPDM\Elementor\Widgets;

/**
 * Single Package Widget.
 * Embeds a single WPDM package.
 */
class PackageWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['pid', 'ltemplate'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'pid' => 'int',
        'ltemplate' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmpackage';
    }

    public function get_title()
    {
        return __('Package', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-download-button';
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
            'ltemplate',
            [
                'label' => __('Link Template', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->getLinkTemplateOptions(),
                'default' => 'link-template-panel'
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

        echo WPDM()->package->shortCodes->singlePackage([
            'id' => $settings['pid'],
            'template' => $settings['ltemplate']
        ]);
    }
}

