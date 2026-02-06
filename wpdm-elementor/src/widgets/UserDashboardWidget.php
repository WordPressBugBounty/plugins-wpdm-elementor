<?php

namespace WPDM\Elementor\Widgets;

/**
 * User Dashboard Widget.
 * Displays user dashboard with favorites and recommendations.
 */
class UserDashboardWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['logo', 'recommended', 'fav', 'signup', 'flaturl'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'logo' => 'url',
        'recommended' => 'text',
        'fav' => 'text',
        'signup' => 'text',
        'flaturl' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmuserdashboard';
    }

    public function get_title()
    {
        return __('User Dashboard', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-thumbnails-half';
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
            'logo',
            [
                'label' => __('Logo URL', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('Logo URL', WPDM_ELEMENTOR),
                'description' => __('Image URL to show on top of the dashboard', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'recommended',
            [
                'label' => __('Recommended', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'recent',
                'placeholder' => __('Category slug or "recent"', WPDM_ELEMENTOR),
                'description' => __('Use category slug or "recent" for latest items', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'fav',
            [
                'label' => __('Show Favorites Section', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '1'
            ]
        );

        $this->add_control(
            'signup',
            [
                'label' => __('Show Login + Signup', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '1',
                'description' => __('Show signup form when user is not logged in', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'flaturl',
            [
                'label' => __('Flat URL', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '0'
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        echo $this->wrapOutput(
            WPDM()->user->dashboard->dashboard($settings),
            'user-dashboard-widget'
        );
    }
}
