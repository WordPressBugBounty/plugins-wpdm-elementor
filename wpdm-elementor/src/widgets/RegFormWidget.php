<?php

namespace WPDM\Elementor\Widgets;

/**
 * Registration Form Widget.
 * Displays a user registration form.
 */
class RegFormWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['captcha', 'verifyemail', 'autologin', 'logo'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'captcha' => 'bool',
        'verifyemail' => 'bool',
        'autologin' => 'bool',
        'logo' => 'url',
    ];

    public function get_name()
    {
        return 'wpdmregform';
    }

    public function get_title()
    {
        return __('Registration Form', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-person';
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
            'captcha',
            [
                'label' => __('Show Captcha', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '1'
            ]
        );

        $this->add_control(
            'verifyemail',
            [
                'label' => __('Verify Email', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '1',
                'description' => __('Send password via email when enabled', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'autologin',
            [
                'label' => __('Auto Login', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '0' => ['title' => __('No', WPDM_ELEMENTOR), 'icon' => 'eicon-close'],
                    '1' => ['title' => __('Yes', WPDM_ELEMENTOR), 'icon' => 'eicon-check']
                ],
                'default' => '0',
                'description' => __('Automatically log in user after registration', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => __('Logo URL', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('Logo URL', WPDM_ELEMENTOR),
                'description' => __('Image URL to show on top of the form', WPDM_ELEMENTOR)
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        echo $this->wrapOutput(
            WPDM()->user->register->form($settings),
            'registration-form-widget'
        );
    }
}
