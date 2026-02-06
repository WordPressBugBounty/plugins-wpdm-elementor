<?php

namespace WPDM\Elementor\Widgets;

/**
 * Frontend/Author Dashboard Widget.
 * Displays author/contributor dashboard.
 */
class FrontendWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = ['logo', 'flaturl', 'hide'];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'logo' => 'url',
        'flaturl' => 'text',
    ];

    public function get_name()
    {
        return 'wpdmfrontend';
    }

    public function get_title()
    {
        return __('Author Dashboard', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-dashboard';
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

        $this->add_control(
            'hide',
            [
                'label' => __('Hide Elements', WPDM_ELEMENTOR),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'settings' => __('Settings', WPDM_ELEMENTOR),
                    'images' => __('Images', WPDM_ELEMENTOR),
                    'cats' => __('Categories', WPDM_ELEMENTOR),
                    'tags' => __('Tags', WPDM_ELEMENTOR)
                ],
                'default' => []
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        // Convert hide array to comma-separated string
        if (isset($settings['hide']) && is_array($settings['hide'])) {
            $settings['hide'] = implode(',', array_map('sanitize_text_field', $settings['hide']));
        }

        echo $this->wrapOutput(
            WPDM()->authorDashboard->dashboard($settings),
            'author-dashboard-widget'
        );
    }
}
