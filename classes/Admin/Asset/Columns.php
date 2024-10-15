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
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

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
            'jquery-ui-sortable',
        ]);

        $this->table_screen = $table_screen;
        $this->table_screens = $table_screens;
        $this->column_type_repository = $column_type_repository;
        $this->list_id = $list_id;
        $this->menu_items = $menu_items;
        $this->favorite_repository = $favorite_repository;
        $this->table_screen_repository = $table_screen_repository;
    }

    public function get_pro_modal_arguments(): array
    {
        $arguments = [];
        $upgrade_page_url = new UtmTags(new Site(Site::PAGE_ABOUT_PRO), 'banner');

        //if not pro, bail early

        $items = [
            'search'      => __('Search any content', 'codepress-admin-columns'),
            'editing'     => __('Inline Edit any content', 'codepress-admin-columns'),
            'bulk-edit'   => __('Bulk Edit any content', 'codepress-admin-columns'),
            'sorting'     => __('Sort any content', 'codepress-admin-columns'),
            'filter'      => __('Filter any content', 'codepress-admin-columns'),
            'column-sets' => __('Create multiple columns sets', 'codepress-admin-columns'),
            'export'      => __('Export table contents to CSV', 'codepress-admin-columns'),
        ];

        $promo = (new AC\PromoCollection())->find_active();
        if ($promo) {
            $arguments['promo'] = [
                'title'          => $promo->get_title(),
                'url'            => (string)$promo->get_url(),
                'button_label'   => sprintf(__('Get %s Off!', 'codepress-admin-columns'), $promo->get_discount() . '%'),
                'discount_until' => sprintf(
                    __("Discount is valid until %s", 'codepress-admin-columns'),
                    $promo->get_date_range()->get_end()->format('j F Y')
                ),
            ];
        }
        
        $features = [];

        foreach ($items as $utm_content => $label) {
            $features[] = [
                'url'   => $upgrade_page_url->add_content('usp-' . $utm_content)->get_url(),
                'label' => $label,
            ];
        }

        $arguments['features'] = $features;

        return $arguments;
    }

    public function register(): void
    {
        parent::register();

        $uninitialized_list_screens = [];

        foreach ($this->table_screens as $table_screen) {
            $uninitialized_list_screens[(string)$table_screen->get_key()] = [
                'screen_link' => (string)$table_screen->get_url()->with_arg(DefaultColumns::QUERY_PARAM, '1'),
            ];
        }

        $this->add_inline_variable('ac_admin_columns', [
            'nonce'                      => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_key'                   => (string)$this->table_screen->get_key(),
            'list_id'                    => (string)$this->list_id,
            'uninitialized_list_screens' => $uninitialized_list_screens,
            'column_groups'              => AC\ColumnGroups::get_groups()->get_all(),
            'menu_items'                 => $this->get_menu_items(),
            'menu_items_favorites'       => $this->encode_favorites(
                $this->get_favorite_table_screens()
            ),
            'menu_groups_opened'         => (new EditorMenuStatus())->get_groups(),
            'urls'                       => [
                'upgrade' => (new UtmTags(new Site(Site::PAGE_ABOUT_PRO), 'upgrade'))->get_url(),
            ],
            'pro_banner'                 => $this->get_pro_modal_arguments(),
        ]);

        $this->localize(
            'ac_admin_columns_i18n',
            new Script\Localize\Translation([
                'errors'     => [
                    'ajax_unknown'   => __('Something went wrong.', 'codepress-admin-columns'),
                    'original_exist' => __(
                        '%s column is already present and can not be duplicated.',
                        'codepress-admin-columns'
                    ),
                ],
                'pro'        => [
                    'modal' => [
                        'title'       => __('Do you like Admin Columns?', 'codepress-admin-columns'),
                        'subtitle'    => __(
                            'Upgrade to PRO, and take Admin Columns to the next level:',
                            'codepress-admin-columns'
                        ),
                        'sort_filter' => __('Sort & Filter on all your content.', 'codepress-admin-columns'),
                        'search'      => __('Search the contents of your columns.', 'codepress-admin-columns'),
                        'bulk_edit'   => __(
                            'Bulk edit any content, including custom fields.',
                            'codepress-admin-columns'
                        ),
                        'inline_edit' => __('Quick edit any content with Inline Editing, including custom fields.'),
                        'export'      => __('Export all column data to CSV.', 'codepress-admin-columns'),
                        'list_tables' => __(
                            'Create multiple list table views with different columns.',
                            'codepress-admin-columns'
                        ),
                        'addons'      => __(
                            'Get add-ons for ACF, WooCommerce and many more',
                            'codepress-admin-columns'
                        ),
                        'upgrade'     => __('Upgrade', 'codepress-admin-columns'),
                    ],
                ],
                'global'     => [
                    'search' => __('Search', 'codepress-admin-columns'),
                    'select' => __('Select', 'codepress-admin-columns'),
                ],
                'menu'       => [
                    'favorites' => __('Favorites', 'codepress-admin-columns'),
                ],
                'settings'   => [
                    'label' => [
                        'select-icon' => __('Select Icon', 'codepress-admin-columns'),
                    ],
                ],
                'pro_banner' => [
                    'title'        => __('Upgrade to', 'codepress-admin-columns'),
                    'title_pro'    => __('Pro', 'codepress-admin-columns'),
                    'sub_title'    => __('Take Admin Columns to the next level:', 'codepress-admin-columns'),
                    'integrations' => __('Includes special integrations for:', 'codepress-admin-columns'),
                ],
                'editor'     => [
                    'label'    => [
                        'save'                 => __('Save', 'codepress-admin-columns'),
                        'add_column'           => __('Add Column', 'codepress-admin-columns'),
                        'add_columns'          => __('Add Columns', 'codepress-admin-columns'),
                        'clear_columns'        => __('Clear columns', 'codepress-admin-columns'),
                        'load_default_columns' => __('Load default columns', 'codepress-admin-columns'),
                        'view'                 => __('View', 'codepress-admin-columns'),
                    ],
                    'sentence' => [
                        'show_default_columns' => __(
                            'The default columns will be shown on the list table when no columns are added.',
                            'codepress-admin-columns'
                        ),
                        'get_started'          => __(
                            'Start by adding columns to your list table.',
                            'codepress-admin-columns'
                        ),
                        'documentation'        => sprintf(
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
                ],
            ])
        );
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