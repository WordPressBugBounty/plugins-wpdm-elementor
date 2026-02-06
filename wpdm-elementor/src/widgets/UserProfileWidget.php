<?php

namespace WPDM\Elementor\Widgets;

/**
 * User Profile Widget.
 * Displays user profile.
 * Note: This widget is currently disabled in Main.php
 */
class UserProfileWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['template'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'template' => 'text',
    ];

    public function get_name()
    {
        return 'wpdm-user-profile';
    }

    public function get_title()
    {
        return __('User Profile', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-user-circle-o';
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

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        echo $this->wrapOutput(
            WPDM()->user->profile->profile($settings),
            'user-profile-widget'
        );
    }
}
