<?php

namespace AC\Admin\Asset;

use AC;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\ColumnCollection;
use AC\Service\DefaultColumns;
use AC\Storage\Repository\EditorFavorites;
use AC\Storage\Repository\EditorMenuStatus;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreenRepository\SortByLabel;
use AC\TableScreen;
use AC\Type\ListScreenId;

class Columns extends Script
{

    private $table_screen;

    private $table_screens;

    private $list_id;

    private $menu_items;

    private $favorite_repository;

    private $table_screen_repository;

    private $column_type_repository;

    public function __construct(
        string $handle,
        Location $location,
        TableScreen $table_screen,
        AC\ColumnTypeRepository $column_type_repository,
        TableScreenCollection $table_screens,
        AC\Admin\MenuListItems $menu_items,
        AC\Table\TableScreenRepository $table_screen_repository,
        EditorFavorites $favorite_repository,
        ListScreenId $list_id = null
    ) {
        parent::__construct($handle, $location, [
            // TODO do we still use these scripts
            'jquery',
            'jquery-ui-slider',
            'jquery-ui-sortable',
            'jquery-touch-punch',
        ]);

        $this->table_screen = $table_screen;
        $this->table_screens = $table_screens;
        $this->column_type_repository = $column_type_repository;
        $this->list_id = $list_id;
        $this->menu_items = $menu_items;
        $this->favorite_repository = $favorite_repository;
        $this->table_screen_repository = $table_screen_repository;
    }

    public function register(): void
    {
        parent::register();

        $this->getAndSetParams();
        $this->getAndSetInlineVariables();
        $this->getTranslation();
    }

    private function getAndSetParams(): void
    {
        $params = $this->getParams();

        foreach ($this->table_screens as $table_screen) {
            $params['uninitialized_list_screens'][(string)$table_screen->get_key()] = $this->getUninitializedListScreens($table_screen);
        }

        wp_localize_script('ac-admin-page-columns', 'AC', $params);
    }

    private function getUninitializedListScreens($table_screen): array
    {
        return [
            'screen_link' => (string)$table_screen->get_url()->with_arg(DefaultColumns::QUERY_PARAM, '1'),
        ];
    }

    private function getParams(): array
    {
        return [
            'list_screen' => $this->table_screen->get_key(),
            'layout'      => (string)$this->list_id,
            'original_columns' => [],
            'uninitialized_list_screens' => [],
            'column_groups' => AC\ColumnGroups::get_groups()->get_all(),
            'i18n' => $this->getI18n(),
        ];
    }

    private function getI18n(): array
    {
        return [
            'value' => __('Value', 'codepress-admin-columns'),
            'label' => __('Label', 'codepress-admin-columns'),
            'clone' => __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
            'error' => __('Invalid response.', 'codepress-admin-columns'),
            'errors' => [
                'save_settings' => __('There was an error during the saving of the column settings.', 'codepress-admin-columns'),
                'loading_column' => __('The column could not be loaded because of an unknown error', 'codepress-admin-columns'),
            ],
        ];
    }

    private function getAndSetInlineVariables(): void
    {
        $this->add_inline_variable('ac_admin_columns', $this->getInlineVariables());
    }

    private function getInlineVariables(): array
    {
        return [
            'nonce' => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_key' => (string)$this->table_screen->get_key(),
            'list_id' => (string)$this->list_id,
            'column_groups' => AC\ColumnGroups::get_groups()->get_all(),
            'menu_items' => $this->get_menu_items(),
            'menu_items_favorites' => $this->encode_favorites(
                $this->get_favorite_table_screens()
            ),
            'menu_groups_opened' => (new EditorMenuStatus())->get_groups(),
        ];
    }

    private function getTranslation(): void
    {
        $this->localize(
            'ac_admin_columns_i18n',
            new Script\Localize\Translation($this->getTranslationArray())
        );
    }

    private function getTranslationArray(): array
    {
        return [
            'errors' => [
                'ajax_unknown' => __('Something went wrong.', 'codepress-admin-columns'),
                'original_exist' => __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
            ],
            'global' => [
                'search' => __('Search', 'codepress-admin-columns'),
                'select' => __('Select', 'codepress-admin-columns'),
            ],
            'menu' => [
                'favorites' => __('Favorites', 'codepress-admin-columns'),
            ],
            'settings' => [
                'label' => [
                    'select-icon' => __('Select Icon', 'codepress-admin-columns'),
                ],
            ],
            'editor' => $this->getEditor(),
        ];
    }

    private function getEditor(): array
    {
        return [
            'label' => [
                'add_column' => __('Add Column', 'codepress-admin-columns'),
                'add_columns' => __('Add Columns', 'codepress-admin-columns'),
                'clear_columns' => __('Clear columns', 'codepress-admin-columns'),
                'load_default_columns' => __('Load default columns', 'codepress-admin-columns'),
            ],
            'sentence' => [
                'show_default_columns' => __('The default columns will be shown on the list table when no columns are added.', 'codepress-admin-columns'),
                'getting_started' => sprintf(
                    __('New to Admin Columns? Take a look at our %s', 'codepress-admin-columns'),
                    sprintf(
                        '<a target="_blank" href="%s">%s</a>',
                        AC\Type\Url\Documentation::create_with_path(
                            AC\Type\Url\Documentation::ARTICLE_GETTING_STARTED
                        ),
                        __('getting started guide', 'codepress-admin-columns')
                    )
                ),
            ],
        ];
    }

    private function get_favorite_table_screens(): TableScreenCollection
    {
        return $this->table_screen_repository->find_all_by_list_keys(
            $this->favorite_repository->find_all(),
            new SortByLabel()
        );
    }

    private function encode_favorites(TableScreenCollection $collection): array
    {
        $keys = [];

        foreach ($collection as $table_screen) {
            $keys[] = (string)$table_screen->get_key();
        }

        return $keys;
    }

    public function get_menu_items(): array
    {
        // TODO
        $options = [];

        foreach ($this->menu_items->all() as $item) {
            $group = $item->get_group();
            $group_name = $group->get_name();

            if ( ! isset($options[$group_name])) {
                $options[$group_name] = [
                    'title'   => $group->get_label(),
                    'icon'    => $group->has_icon() ? $group->get_icon() : '',
                    'options' => [],
                ];
            }

            $options[$group_name]['options'][$item->get_key()] = $item->get_label();
        }

        return $options;
    }

    private function get_original_types(): array
    {
        $types = [];
        foreach ($this->column_type_repository->find_all_by_orginal($this->table_screen) as $column) {
            $types[] = $column->get_type();
        }

        return $types;
    }

    private function encode_column_types(ColumnCollection $collection): array
    {
        $encode = [];

        $original_types = $this->get_original_types();

        // TODO cache
        $groups = AC\ColumnGroups::get_groups();

        foreach ($collection as $column) {
            $encode[] = [
                'label'     => $column->get_label(),
                'value'     => $column->get_type(),
                'group'     => $groups->get($column->get_group())['label'] ?? 'default',
                'group_key' => $column->get_group(),
                'original'  => in_array($column->get_type(), $original_types, true),
            ];
        }

        return $encode;
    }

}