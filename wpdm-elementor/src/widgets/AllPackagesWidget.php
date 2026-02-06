<?php

namespace WPDM\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * All Packages Widget.
 * Displays packages in a table/grid format with customizable columns.
 */
class AllPackagesWidget extends BaseWidget
{
    /**
     * Expected settings keys for this widget.
     */
    private const SETTINGS_KEYS = [
        'categories', 'orderby', 'order', 'items_per_page',
        'table_columns', 'cols', 'colheads', 'jstable', 'login'
    ];

    /**
     * Settings sanitization rules.
     */
    private const SANITIZERS = [
        'orderby' => 'orderby',
        'order' => 'order',
        'items_per_page' => 'int',
        'cols' => 'text',
        'colheads' => 'text',
        'jstable' => 'text',
        'login' => 'text',
    ];

    /**
     * Available WPDM data fields for table columns.
     *
     * @return array Field key => label pairs.
     */
    private function getDataFieldOptions(): array
    {
        return [
            // Basic Info
            'title'          => __('Title', WPDM_ELEMENTOR),
            'page_link'           => __('Title with Link', WPDM_ELEMENTOR),
            'description'    => __('Description', WPDM_ELEMENTOR),
            'excerpt'        => __('Excerpt', WPDM_ELEMENTOR),

            // Taxonomy
            'categories'     => __('Categories', WPDM_ELEMENTOR),
            'tags'           => __('Tags', WPDM_ELEMENTOR),

            // Download
            'download_link'  => __('Download Button', WPDM_ELEMENTOR),
            'download_url'   => __('Download URL', WPDM_ELEMENTOR),
            'download_count' => __('Download Count', WPDM_ELEMENTOR),

            // File Info
            'file_size'      => __('File Size', WPDM_ELEMENTOR),
            'file_count'     => __('File Count', WPDM_ELEMENTOR),
            'file_type'      => __('File Type', WPDM_ELEMENTOR),
            'version'        => __('Version', WPDM_ELEMENTOR),

            // Dates
            'publish_date'   => __('Publish Date', WPDM_ELEMENTOR),
            'update_date'    => __('Update Date', WPDM_ELEMENTOR),

            // Media
            'thumb'          => __('Thumbnail', WPDM_ELEMENTOR),
            'preview'        => __('Preview', WPDM_ELEMENTOR),
            'icon'           => __('File Icon', WPDM_ELEMENTOR),

            // Author
            'author'         => __('Author Name', WPDM_ELEMENTOR),
            //'author_pic'    => __('Author Picture', WPDM_ELEMENTOR),

            // Stats
            'view_count'     => __('View Count', WPDM_ELEMENTOR),

        ];
    }

    public function get_name()
    {
        return 'wpdm-all-packages';
    }

    public function get_title()
    {
        return __('Packages Table', WPDM_ELEMENTOR);
    }

    public function get_icon()
    {
        return 'eicon-table';
    }

    public function get_keywords(): array
    {
        return ['wpdm', 'download', 'table', 'packages', 'list', 'grid'];
    }

    protected function register_controls()
    {
        // Content Section - Query
        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', WPDM_ELEMENTOR),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'    => __('Filter by Categories', WPDM_ELEMENTOR),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => $this->getCategoryOptions(),
                'default'  => []
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => __('Order By', WPDM_ELEMENTOR),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'date'           => __('Date', WPDM_ELEMENTOR),
                    'title'          => __('Title', WPDM_ELEMENTOR),
                    'download_count' => __('Download Count', WPDM_ELEMENTOR),
                    'rand'           => __('Random', WPDM_ELEMENTOR),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => __('Order', WPDM_ELEMENTOR),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'ASC'  => ['title' => __('Ascending', WPDM_ELEMENTOR), 'icon' => 'eicon-arrow-up'],
                    'DESC' => ['title' => __('Descending', WPDM_ELEMENTOR), 'icon' => 'eicon-arrow-down']
                ],
                'default' => 'DESC',
                'toggle'  => false
            ]
        );

        $this->add_control(
            'items_per_page',
            [
                'label'   => __('Items Per Page', WPDM_ELEMENTOR),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 100,
                'default' => 10
            ]
        );

        $this->end_controls_section();

        // Content Section - Table Columns
        $this->start_controls_section(
            'columns_section',
            [
                'label' => __('Table Columns', WPDM_ELEMENTOR),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'column_heading',
            [
                'label'       => __('Column Heading', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Column', WPDM_ELEMENTOR),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'column_fields',
            [
                'label'       => __('Data Fields', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->getDataFieldOptions(),
                'default'     => ['title'],
                'label_block' => true,
                'description' => __('Select one or more fields to display in this column', WPDM_ELEMENTOR),
            ]
        );

        $repeater->add_control(
            'column_width',
            [
                'label'       => __('Column Width', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('e.g., 100px or 20%', WPDM_ELEMENTOR),
                'description' => __('Leave empty for auto width', WPDM_ELEMENTOR),
            ]
        );

        $repeater->add_control(
            'column_align',
            [
                'label'   => __('Text Align', WPDM_ELEMENTOR),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', WPDM_ELEMENTOR), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', WPDM_ELEMENTOR), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', WPDM_ELEMENTOR), 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'left',
                'toggle'  => false,
            ]
        );

        $this->add_control(
            'table_columns',
            [
                'label'       => __('Columns', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'column_heading' => __('Title', WPDM_ELEMENTOR),
                        'column_fields'  => ['link'],
                        'column_width'   => '',
                        'column_align'   => 'left',
                    ],
                    [
                        'column_heading' => __('Categories', WPDM_ELEMENTOR),
                        'column_fields'  => ['categories'],
                        'column_width'   => '',
                        'column_align'   => 'left',
                    ],
                    [
                        'column_heading' => __('Download', WPDM_ELEMENTOR),
                        'column_fields'  => ['download_link'],
                        'column_width'   => '120px',
                        'column_align'   => 'center',
                    ],
                ],
                'title_field' => '{{{ column_heading }}}',
            ]
        );

        $this->add_control(
            'legacy_mode_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'use_legacy_mode',
            [
                'label'        => __('Use Legacy Mode', WPDM_ELEMENTOR),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', WPDM_ELEMENTOR),
                'label_off'    => __('No', WPDM_ELEMENTOR),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => __('Enable to use the old text-based column configuration', WPDM_ELEMENTOR),
            ]
        );

        $this->add_control(
            'cols',
            [
                'label'       => __('Data Field Names (Legacy)', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'title|categories',
                'description' => __('Column separator: | Multiple fields per column: ,', WPDM_ELEMENTOR),
                'condition'   => [
                    'use_legacy_mode' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'colheads',
            [
                'label'       => __('Column Headings (Legacy)', WPDM_ELEMENTOR),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Title|Categories',
                'description' => __('e.g: Title|Categories|Download::100px', WPDM_ELEMENTOR),
                'condition'   => [
                    'use_legacy_mode' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Section - Table Options
        $this->start_controls_section(
            'options_section',
            [
                'label' => __('Table Options', WPDM_ELEMENTOR),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'jstable',
            [
                'label'        => __('Enable DataTable.js', WPDM_ELEMENTOR),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', WPDM_ELEMENTOR),
                'label_off'    => __('No', WPDM_ELEMENTOR),
                'return_value' => '1',
                'default'      => '',
                'description'  => __('Adds sorting, searching, and pagination features', WPDM_ELEMENTOR),
            ]
        );

        $this->add_control(
            'login',
            [
                'label'        => __('Require Login', WPDM_ELEMENTOR),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', WPDM_ELEMENTOR),
                'label_off'    => __('No', WPDM_ELEMENTOR),
                'return_value' => '1',
                'default'      => '',
                'description'  => __('Only show table to logged-in users', WPDM_ELEMENTOR),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Convert repeater columns to legacy format for WPDM shortcode.
     *
     * @param array $columns Repeater columns data.
     * @return array ['cols' => string, 'colheads' => string]
     */
    private function convertColumnsToLegacyFormat(array $columns): array
    {
        $cols = [];
        $colheads = [];

        foreach ($columns as $column) {
            // Get fields for this column
            $fields = $column['column_fields'] ?? ['title'];
            if (!is_array($fields)) {
                $fields = [$fields];
            }
            $cols[] = implode(',', $fields);

            // Build column heading with optional width
            $heading = $column['column_heading'] ?? 'Column';
            $width = $column['column_width'] ?? '';

            if (!empty($width)) {
                $heading .= '::' . $width;
            }
            $colheads[] = $heading;
        }

        return [
            'cols'     => implode('|', $cols),
            'colheads' => implode('|', $colheads),
        ];
    }

    protected function render()
    {
        $settings = $this->getCleanSettings(self::SETTINGS_KEYS);
        $settings = $this->sanitizeSettings($settings, self::SANITIZERS);

        // Convert categories array to comma-separated string
        if (isset($settings['categories']) && is_array($settings['categories'])) {
            $settings['categories'] = implode(',', array_map('sanitize_text_field', $settings['categories']));
        }

        // Remove empty categories - ensure it's a string before checking
        $categories = $settings['categories'] ?? '';
        if (!is_string($categories) || empty(trim($categories))) {
            unset($settings['categories']);
        }

        // Check if using new repeater mode or legacy mode
        $use_legacy = !empty($settings['use_legacy_mode']) && $settings['use_legacy_mode'] === 'yes';

        if (!$use_legacy && !empty($settings['table_columns'])) {
            // Convert repeater data to legacy format for WPDM
            $converted = $this->convertColumnsToLegacyFormat($settings['table_columns']);
            $settings['cols'] = $converted['cols'];
            $settings['colheads'] = $converted['colheads'];
        }

        // Remove non-shortcode settings
        unset($settings['table_columns']);
        unset($settings['use_legacy_mode']);

        // Convert switcher values
        $settings['jstable'] = !empty($settings['jstable']) ? '1' : '0';
        $settings['login'] = !empty($settings['login']) ? '1' : '0';

        echo WPDM()->package->shortCodes->allPackages($settings);
    }
}
