<?php

namespace WPDM\Elementor\Widgets;

/**
 * Login Form Widget.
 * Displays a user login form.
 */
class LoginFormWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['redirect', 'logo', 'regurl', 'note_before', 'note_after'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'redirect' => 'url',
        'logo' => 'url',
        'regurl' => 'url',
        'note_before' => 'html',
        'note_after' => 'html',
    ];

    public function get_name()
    {
        return 'wpdmloginform';
    }

    public function get_title()
    {
        return __('Login Form', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-site-identity';
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
            'redirect',
            [
                'label' => __('Redirect URL', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('Redirect URL', WPDM_ELEMENTOR),
                'description' => __('URL to redirect after login', WPDM_ELEMENTOR)
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

        $this->add_control(
            'regurl',
            [
                'label' => __('Registration URL', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('Registration URL', WPDM_ELEMENTOR),
                'description' => __('Custom signup page URL for this login form', WPDM_ELEMENTOR)
            ]
        );

        $this->add_control(
            'note_before',
            [
                'label' => __('Note Before', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'placeholder' => __('Text to show above the form', WPDM_ELEMENTOR),
            ]
        );

        $this->add_control(
            'note_after',
            [
                'label' => __('Note After', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'placeholder' => __('Text to show below the form', WPDM_ELEMENTOR),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        echo $this->wrapOutput(
            WPDM()->user->login->form($settings),
            'login-form-widget'
        );
    }
}
