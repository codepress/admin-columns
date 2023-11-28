<?php

namespace AC\Admin\Asset;

use AC;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Controller\DefaultColumns;

class Columns extends Script
{

    /**
     * @var AC\ListScreen[]
     */
    private $list_screens;

    private $list_key;

    private $list_id;

    /**
     * @var AC\ListScreen
     */
    private $list_screen;

    private $menu;

    public function __construct(
        string $handle,
        Location $location,
        AC\ListScreen $list_screen,
        array $list_screens,
        string $list_key,
        AC\Admin\Section\Partial\Menu $menu,
        string $list_id = null
    ) {
        parent::__construct($handle, $location, [
            'jquery',
            'jquery-ui-slider',
            'jquery-ui-sortable',
            'jquery-touch-punch',
        ]);

        $this->list_screens = $list_screens;
        $this->list_key = $list_key;
        $this->list_id = $list_id;
        $this->list_screen = $list_screen;
        $this->menu = $menu;
    }

    public function register(): void
    {
        parent::register();

        // TODO Remove AC variable and use more specific
        $params = [
            '_ajax_nonce'                => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_screen'                => $this->list_key,
            'layout'                     => $this->list_id,
            'original_columns'           => [],
            'uninitialized_list_screens' => [],
            'column_types'               => $this->get_column_types($this->list_screen),
            'column_groups'              => AC\ColumnGroups::get_groups()->get_all(),
            'i18n'                       => [
                'value'  => __('Value', 'codepress-admin-columns'),
                'label'  => __('Label', 'codepress-admin-columns'),
                'clone'  => __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
                'error'  => __('Invalid response.', 'codepress-admin-columns'),
                'errors' => [
                    'save_settings'  => __(
                        'There was an error during saving the column settings.',
                        'codepress-admin-columns'
                    ),
                    'loading_column' => __(
                        'The column could not be loaded because of an unknown error',
                        'codepress-admin-columns'
                    ),
                ],
            ],
        ];

        foreach ($this->list_screens as $list_screen) {
            $params['uninitialized_list_screens'][$list_screen->get_key()] = [
                'screen_link' => (string)$list_screen->get_table_url()->with_arg(DefaultColumns::QUERY_PARAM, '1'),
            ];
        }

        wp_localize_script('ac-admin-page-columns', 'AC', $params);

        // TODO Needed for UI2 Remove part above
        $this->add_inline_variable('ac_admin_columns', [
            'menu_items'     => $this->menu->get_menu_items(),
            'list_key'       => $this->list_key,
            'list_screen_id' => $this->list_id,
            'column_types'   => $this->get_column_types($this->list_screen),
            'column_groups'  => AC\ColumnGroups::get_groups()->get_all(),
        ]);

        $this->localize(
            'ac_admin_columns_i18n',
            new Script\Localize\Translation([
                'global'   => [
                    'search' => __('Search', 'codepress-admin-columns'),
                    'select' => __('Select', 'codepress-admin-columns'),
                ],
                'settings' => [
                    'label' => [
                        'select-icon' => __('Select Icon', 'codepress-admin-columns'),
                    ],
                ],
            ])
        );
    }

    private function get_column_types(AC\ListScreen $list_screen): array
    {
        $column_types = [];

        foreach ($list_screen->get_column_types() as $column) {
            $column_types[] = [
                'label'     => $column->get_label(),
                'value'     => $column->get_type(),
                'group'     => AC\ColumnGroups::get_groups()->get($column->get_group())['label'],
                'group_key' => $column->get_group(),
                'original'  => $column->is_original(),
            ];
        }

        return $column_types;
    }

}