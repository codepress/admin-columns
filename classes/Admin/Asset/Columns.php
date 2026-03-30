<?php

namespace AC\Admin\Asset;

use AC;
use AC\Admin\Banner\BannerContextResolver;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Capabilities;
use AC\Form\NonceFactory;
use AC\Storage\Repository\EditorFavorites;
use AC\Storage\Repository\EditorMenuStatus;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreenRepository\SortByLabel;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\StartingPrice;
use AC\Type\Url\Documentation;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class Columns extends Script
{

    private TableScreen $table_screen;

    private TableScreenCollection $table_screens;

    private AC\Admin\MenuListItems $menu_items;

    private EditorFavorites $favorite_repository;

    private AC\Table\TableScreenRepository $table_screen_repository;

    private AC\ColumnGroups $column_groups;

    private AC\Promo\PromoRepository $promos;

    private Location $parent_location;

    private AC\Integration\IntegrationRepository $integration_repository;

    private bool $is_pro_active;

    private ?ListScreenId $list_id;

    private BannerContextResolver $banner_context_resolver;

    public function __construct(
        string $handle,
        Location $location,
        TableScreen $table_screen,
        TableScreenCollection $table_screens,
        AC\Admin\MenuListItems $menu_items,
        AC\Table\TableScreenRepository $table_screen_repository,
        EditorFavorites $favorite_repository,
        AC\ColumnGroups $column_groups,
        AC\Promo\PromoRepository $promos,
        AC\Integration\IntegrationRepository $integration_repository,
        Location $parent_location,
        bool $is_pro_active,
        BannerContextResolver $banner_context_resolver,
        ?ListScreenId $list_id = null
    ) {
        parent::__construct($handle, $location, [
            'jquery-ui-sortable',
            Script\GlobalTranslationFactory::HANDLE,
        ]);

        $this->table_screen = $table_screen;
        $this->table_screens = $table_screens;
        $this->menu_items = $menu_items;
        $this->favorite_repository = $favorite_repository;
        $this->table_screen_repository = $table_screen_repository;
        $this->column_groups = $column_groups;
        $this->promos = $promos;
        $this->parent_location = $parent_location;
        $this->integration_repository = $integration_repository;
        $this->is_pro_active = $is_pro_active;
        $this->banner_context_resolver = $banner_context_resolver;
        $this->list_id = $list_id;
    }

    public function get_pro_modal_arguments(): array
    {
        $arguments = [];
        $upgrade_page_url = new UtmTags(Site::create_admin_columns_pro(), 'banner');

        $plural = $this->table_screen->get_labels()->get_plural();
        $singular = $this->table_screen->get_labels()->get_singular();

        if (mb_strlen($plural) > 30) {
            $plural = __('content', 'codepress-admin-columns');
            $singular = __('item', 'codepress-admin-columns');
        }

        $plural_lower = mb_strtolower($plural);
        $singular_lower = mb_strtolower($singular);

        $items = [
            'editing'     => __('Inline edit directly in the table', 'codepress-admin-columns'),
            'sorting'     => __('Sort and filter on any column', 'codepress-admin-columns'),
            'bulk-edit'   => sprintf(
            /* translators: %s: post type label plural (e.g. "posts", "pages") */
                __('Bulk edit hundreds of %s at once', 'codepress-admin-columns'),
                $plural_lower
            ),
            'export'      => __('Export table data to CSV', 'codepress-admin-columns'),
            'column-sets' => __('Multiple views per screen', 'codepress-admin-columns'),
        ];

        if (current_user_can(Capabilities::MANAGE)) {
            $promo = $this->promos->find_active();

            if ($promo) {
                $arguments['promo'] = [
                    'title'          => $promo->get_title(),
                    'url'            => (string)$promo->get_url(),
                    'button_label'   => $promo->get_button_label(),
                    'discount_until' => sprintf(
                        __("Discount is valid until %s", 'codepress-admin-columns'),
                        $promo->get_date_range()->get_end()->format('j F Y')
                    ),
                ];
            }
        }

        $integration_priority = [
            'ac-addon-woocommerce',
            'ac-addon-acf',
            'ac-addon-yoast-seo',
            'ac-addon-gravityforms',
            'ac-addon-metabox',
            'ac-addon-events-calendar',
            'ac-addon-buddypress',
            'ac-addon-jetengine',
            'ac-addon-pods',
            'ac-addon-types',
            'ac-addon-media-library-assistant',
            'ac-addon-rankmath',
            'ac-addon-seopress',
        ];

        $active_integrations = [];

        foreach ($this->integration_repository->find_all_by_active_plugins() as $integration) {
            $active_integrations[$integration->get_slug()] = $integration;
        }

        $integrations = [];

        foreach ($integration_priority as $slug) {
            if ( ! isset($active_integrations[$slug])) {
                continue;
            }

            $integration = $active_integrations[$slug];
            $integrations[] = [
                'url'   => (string)$integration->get_url(),
                'label' => $integration->get_title(),
            ];

            if (count($integrations) >= 2) {
                break;
            }
        }

        $features = [];

        foreach ($items as $utm_content => $label) {
            $features[] = [
                'url'   => $upgrade_page_url->with_content('usp-' . $utm_content)->get_url(),
                'label' => $label,
            ];
        }

        $arguments['title'] = sprintf(
        /* translators: %s: post type label plural (e.g. "posts", "pages") */
            __('Manage your %s faster', 'codepress-admin-columns'),
            $plural_lower
        );
        $arguments['description'] = sprintf(
        /* translators: 1: post type label plural, 2: post type label singular */
            __(
                'Turn your %1$s overview into a workspace for sorting, editing, filtering, and exporting - without opening a single %2$s.',
                'codepress-admin-columns'
            ),
            $plural_lower,
            $singular_lower
        );
        $arguments['upgrade_cta'] = sprintf(
        /* translators: %s: post type label plural (e.g. "posts", "pages") */
            __('Unlock faster %s management ', 'codepress-admin-columns'),
            $singular_lower
        );
        $arguments['upgrade_cta_price'] = sprintf(
        /* translators: %s: price (e.g. $79) */
            __('from %s/year', 'codepress-admin-columns'),
            StartingPrice::get()
        );
        $arguments['features'] = $features;
        $arguments['integrations'] = $integrations;
        $arguments['promo_url'] = $upgrade_page_url->get_url();
        $arguments['discount'] = 10;

        return $arguments;
    }

    private function get_pro_banner_context(): ?array
    {
        $context = $this->banner_context_resolver->resolve($this->table_screen);

        if ($context === null) {
            return null;
        }

        return $context->get_arguments($this->table_screen);
    }

    /**
     * @return array<int, array{list_key: string, message: string, type: string, cta_label?: string, cta_url?: string}>
     */
    private function get_screen_notices(): array
    {
        return apply_filters('ac/admin/screen_notices', []);
    }

    private function encode_groups(AC\Type\Groups $groups): array
    {
        $encode = [];

        foreach ($groups as $group) {
            $encode[] = [
                'slug'     => $group->get_slug(),
                'label'    => $group->get_label(),
                'priority' => $group->get_priority(),
                'icon'     => $group->has_icon() ? $group->get_icon() : '',
            ];
        }

        return $encode;
    }

    public function register(): void
    {
        parent::register();

        $uninitialized_table_screens = [];

        foreach ($this->table_screens as $table_screen) {
            $uninitialized_table_screens[(string)$table_screen->get_id()] = [
                'screen_link' => (string)$table_screen->get_url()->with_arg('save-default-headings', '1'),
            ];
        }

        $this->add_inline_variable('ac_admin_columns', [
            'assets'                     => $this->parent_location->with_suffix('assets')->get_url(),
            'nonce'                      => NonceFactory::create_ajax()->create(),
            'show_column_info'           => (new AC\Admin\Preference\ScreenOptions())->is_active('show_column_info'),
            'is_pro'                     => $this->is_pro_active,
            'list_key'                   => (string)$this->table_screen->get_id(),
            'list_id'                    => (string)$this->list_id,
            'uninitialized_list_screens' => ! empty($uninitialized_table_screens) ? $uninitialized_table_screens : null,
            'column_groups'              => $this->encode_groups($this->column_groups->find_all()),
            'menu_items'                 => $this->get_menu_items(),
            'menu_items_favorites'       => $this->encode_favorites($this->get_favorite_table_screens()),
            'menu_groups_opened'         => (new EditorMenuStatus())->get_groups(),
            'urls'                       => [
                'upgrade'    => (new UtmTags(Site::create_admin_columns_pro(), 'upgrade'))->get_url(),
                'learn_more' => (new UtmTags(Site::create_admin_columns_pro(), 'learn-more'))->get_url(),
            ],
            'pro_banner'                 => $this->is_pro_active ? null : $this->get_pro_modal_arguments(),
            'pro_banner_context'         => $this->is_pro_active ? null : $this->get_pro_banner_context(),
            'screen_notices'             => $this->get_screen_notices(),
            'review'                     => [
                'doc_url'     => (new UtmTags(new Documentation(), 'review-notice'))->get_url(),
                'upgrade_url' => (new UtmTags(Site::create_admin_columns_pro(), 'upgrade'))->get_url(),
            ],
            'support'                    => [
                'description' => sprintf(
                    __(
                        "For full documentation, bug reports, feature suggestions and other tips visit %s.",
                        'codepress-admin-columns'
                    ),
                    sprintf(
                        '<a target="_blank" href="%s">%s</a>',
                        (new UtmTags(new Documentation()))->get_url(),
                        __('the Admin Columns website', 'codepress-admin-columns')
                    )
                ),
                'review'      => (new UtmTags(new Documentation(), 'review-notice'))->get_url(),
            ],
            'confirm_delete'             => (bool)apply_filters('ac/delete_confirmation', true),
            'table_elements'             => [
                'default'  => [
                    __('Filters', 'codepress-admin-columns'),
                    __('Status (Quick Links)', 'codepress-admin-columns'),
                    __('Search', 'codepress-admin-columns'),
                    __('Bulk Actions', 'codepress-admin-columns'),
                    __('Row Actions (Below Title)', 'codepress-admin-columns'),
                ],
                'features' => [
                    __('Inline Edit', 'codepress-admin-columns'),
                    __('Bulk Edit', 'codepress-admin-columns'),
                    __('Bulk Delete', 'codepress-admin-columns'),
                    __('Smart Filters', 'codepress-admin-columns'),
                    __('Export', 'codepress-admin-columns'),
                    __('Conditional Formatting', 'codepress-admin-columns'),
                    __('Add Row (Quick Add)', 'codepress-admin-columns'),
                    __('Resize Columns', 'codepress-admin-columns'),
                    __('Resize Columns', 'codepress-admin-columns'),
                ],
            ],
        ]);

        $this->localize(
            'ac_admin_columns_i18n',
            new Script\Localize\Translation([
                'table_views' => [
                    'delete_view' => __('Delete view', 'codepress-admin-columns'),
                ],
                'errors'      => [
                    'ajax_unknown'   => __('Something went wrong.', 'codepress-admin-columns'),
                    'original_exist' => __(
                        '%s column is already present and can not be duplicated.',
                        'codepress-admin-columns'
                    ),
                ],
                'pro'         => [
                    'modal'    => [
                        'title'      => __('This is a Pro feature', 'codepress-admin-columns'),
                        'subtitle'   => __(
                            'Upgrade to Pro and take Admin Columns to the next level.',
                            'codepress-admin-columns'
                        ),
                        'also_get'   => __('With Pro you also get', 'codepress-admin-columns'),
                        'trusted_by' => sprintf(
                            '%s · %s',
                            sprintf(
                            /* translators: %s: number of sites (e.g. 250,000+) */
                                __('Trusted by %s WordPress sites', 'codepress-admin-columns'),
                                '250,000+'
                            ),
                            sprintf(
                            /* translators: %s: rating (e.g. 4.9) */
                                __('%s on Trustpilot', 'codepress-admin-columns'),
                                '4.9'
                            )
                        ),
                        'guarantee'  => __('30-day money-back guarantee', 'codepress-admin-columns'),
                        'see_all'    => __('See all Pro features', 'codepress-admin-columns'),
                        'upgrade'    => sprintf(
                            '%s - %s',
                            __('Upgrade', 'codepress-admin-columns'),
                            sprintf(
                            /* translators: %s: price (e.g. $79) */
                                __('from %s/year', 'codepress-admin-columns'),
                                StartingPrice::get()
                            )
                        ),
                        'features'   => [
                            'inline_edit' => [
                                'badge'       => __('Inline Editing is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __(
                                    'Edit content directly in the list table',
                                    'codepress-admin-columns'
                                ),
                                'description' => __(
                                    'Click any cell to edit - no need to open each item individually. Works with custom fields, taxonomies, and more.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __(
                                    'Edit content directly in the list table',
                                    'codepress-admin-columns'
                                ),
                            ],
                            'bulk_edit'   => [
                                'badge'       => __('Bulk Editing is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __('Edit hundreds of items at once', 'codepress-admin-columns'),
                                'description' => __(
                                    'Select multiple items and update them in one go - change statuses, categories, custom fields, and more.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Bulk edit hundreds of items at once', 'codepress-admin-columns'),
                            ],
                            'sorting'     => [
                                'badge'       => __('Sorting is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __(
                                    'Sort and filter your content by any column',
                                    'codepress-admin-columns'
                                ),
                                'description' => __(
                                    'Instantly find what you need - sort by any column and filter by custom fields, dates, statuses, and more.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Sort and filter by any column', 'codepress-admin-columns'),
                            ],
                            'filter'      => [
                                'badge'       => __('Filtering is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __(
                                    'Sort and filter your content by any column',
                                    'codepress-admin-columns'
                                ),
                                'description' => __(
                                    'Instantly find what you need - sort by any column and filter by custom fields, dates, statuses, and more.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Sort and filter by any column', 'codepress-admin-columns'),
                            ],
                            'search'      => [
                                'badge'       => __('Smart Filtering is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __(
                                    'Filter your content with saved filters',
                                    'codepress-admin-columns'
                                ),
                                'description' => __(
                                    'Create smart filters combining multiple conditions. Save them as segments and reuse them with one click.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Smart filters with saved segments', 'codepress-admin-columns'),
                            ],
                            'export'      => [
                                'badge'       => __('Export is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __('Export your list table data to CSV', 'codepress-admin-columns'),
                                'description' => __(
                                    'Export exactly what you see - your columns, your filters, your data. Ready for spreadsheets or further processing.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Export table data to CSV', 'codepress-admin-columns'),
                            ],
                            'list_tables' => [
                                'badge'       => __('Column Sets is a Pro feature', 'codepress-admin-columns'),
                                'headline'    => __('Create multiple table views', 'codepress-admin-columns'),
                                'description' => __(
                                    'Set up different column configurations for different tasks. Switch between views with one click.',
                                    'codepress-admin-columns'
                                ),
                                'label'       => __('Multiple table views per screen', 'codepress-admin-columns'),
                            ],
                        ],
                    ],
                    'banner'   => [
                        'badge'                => __('Admin Columns Pro', 'codepress-admin-columns'),
                        'title'                => __('Manage your content faster', 'codepress-admin-columns'),
                        'description'          => __(
                            'Turn your list tables into a powerful command center for sorting, editing, filtering, and exporting content.',
                            'codepress-admin-columns'
                        ),
                        'features_label'       => __('With Pro you get', 'codepress-admin-columns'),
                        'works_with'           => __('Works with:', 'codepress-admin-columns'),
                        'trust'                => sprintf(
                            '%s · %s',
                            sprintf(
                            /* translators: %s: number of sites (e.g. 250,000+) */
                                __('%s sites', 'codepress-admin-columns'),
                                '250,000+'
                            ),
                            sprintf(
                            /* translators: %s: rating (e.g. 4.9) */
                                __('%s on Trustpilot', 'codepress-admin-columns'),
                                '4.9'
                            )
                        ),
                        'upgrade_cta'          => __('Upgrade to Pro', 'codepress-admin-columns'),
                        'upgrade_cta_price'    => sprintf(
                        /* translators: %s: price (e.g. $79) */
                            __('from %s/year', 'codepress-admin-columns'),
                            StartingPrice::get()
                        ),
                        'guarantee'            => __('30-day money-back guarantee', 'codepress-admin-columns'),
                        'see_all'              => __('See all Pro features', 'codepress-admin-columns'),
                        'discount_title'       => __('Get 10% off Admin Columns Pro', 'codepress-admin-columns'),
                        'discount_description' => __(
                            "Upgrade today and we'll send you a discount code for your first year.",
                            'codepress-admin-columns'
                        ),
                        'your_email'           => __('Your email address', 'codepress-admin-columns'),
                        'send_discount'        => __('Get discount code', 'codepress-admin-columns'),
                        'discount_note'        => __('No spam. Just your discount code.', 'codepress-admin-columns'),
                    ],
                    'settings' => [
                        'status'       => [
                            'status'      => __('Active', 'codepress-admin-columns'),
                            'activate'    => __('Activate', 'codepress-admin-columns'),
                            'description' => __(
                                'Toggle to show or hide this view on the list table.',
                                'codepress-admin-columns'
                            ),
                        ],
                        'conditionals' => [
                            'select_roles' => __('Select roles', 'codepress-admin-columns'),
                            'select_users' => __('Select users', 'codepress-admin-columns'),
                            'conditionals' => __('Conditionals', 'codepress-admin-columns'),
                            'description'  => __(
                                'Make this table view available only for specific users or roles.',
                                'codepress-admin-columns'
                            ),
                        ],
                        'elements'     => [
                            'table_elements' => __('Table Elements', 'codepress-admin-columns'),
                            'description'    => __(
                                'Show or hide elements from the table list screen.',
                                'codepress-admin-columns'
                            ),
                            'default'        => __('default Elements', 'codepress-admin-columns'),
                            'features'       => __('Features', 'codepress-admin-columns'),
                        ],

                        'preferences' => [
                            'preferences'          => __('Preferences', 'codepress-admin-columns'),
                            'description'          => __(
                                'Set default settings that users will see when they visit the list table.',
                                'codepress-admin-columns'
                            ),
                            'horizontal_scrolling' => __('Horizontal Scrolling', 'codepress-admin-columns'),
                            'sorting'              => __('Initial Sorting', 'codepress-admin-columns'),
                            'segments'             => __('Pre-applied Filters', 'codepress-admin-columns'),
                            'no_segments'          => __('No saved filters available.', 'codepress-admin-columns'),
                            'primary_column'       => __('Primary Column', 'codepress-admin-columns'),
                            'wrapping'             => __('Wrapping', 'codepress-admin-columns'),
                            'wrapping_options'     => [
                                'wrap' => sprintf(
                                    '%s (%s)',
                                    _x('Wrap', 'wrapping_option', 'codepress-admin-columns'),
                                    __('default', 'codepress-admin-columns')
                                ),
                                'clip' => _x('Clip', 'wrapping_option', 'codepress-admin-columns'),
                            ],
                            'unlock'               => __(
                                'Unlock with Admin Columns Pro',
                                'codepress-admin-columns'
                            ),
                        ],
                    ],
                ],
                'notices'     => [
                    'unsaved_changes'       => __('You have unsaved changes', 'codepress-admin-columns'),
                    'unsaved_changes_leave' => __(
                        'If you leave this page, all unsaved changes will be lost. Are you sure you want to leave?',
                        'codepress-admin-columns'
                    ),
                    'inactive'              => sprintf(
                        __(
                            'This table view is %s and won’t appear on the list table.',
                            'codepress-admin-columns'
                        ),
                        sprintf('<strong>%s</strong >', __('not active', 'codepress-admin-columns'))
                    ),
                    'not_saved_settings'    => sprintf(
                        '%s %s',
                        sprintf(
                            __('These settings are %s.', 'codepress-admin-columns'),
                            sprintf('<strong>%s</strong >', __('not saved', 'codepress-admin-columns'))
                        ),
                        sprintf(
                            __(
                                'Click %s to apply these column settings to the list table.',
                                'codepress-admin-columns'
                            ),
                            sprintf('<strong>%s</strong >', __('Save', 'codepress-admin-columns')),
                        )
                    ),
                ],
                'support'     => [
                    'title' => __('Support', 'codepress-admin-columns'),
                ],
                'review'      => [
                    'happy'        => __('Missing something?', 'codepress-admin-columns'),
                    'help_improve' => __(
                        'Help us improve Admin Columns with one quick click.',
                        'codepress-admin-columns'
                    ),
                    'all_good'     => __('All good', 'codepress-admin-columns'),
                    'need_feature' => __('Need a feature', 'codepress-admin-columns'),
                    'glad'         => __("Glad to hear it!", 'codepress-admin-columns'),
                    'give_rating'  => __(
                        'A quick rating or tweet helps us a lot.',
                        'codepress-admin-columns'
                    ),
                    'whats_wrong'  => __('Let us know!', 'codepress-admin-columns'),
                    'checkdocs'    => __(
                        'Tell us what feature you need on the WordPress.org support forum.',
                        'codepress-admin-columns'
                    ),
                    'check_pro'    => __('See Pro features', 'codepress-admin-columns'),
                    'docs'         => __('Docs', 'codepress-admin-columns'),
                    'forum'        => __('Forum', 'codepress-admin-columns'),
                    'rate'         => __('Rate', 'codepress-admin-columns'),
                    'tweet'        => __('Tweet', 'codepress-admin-columns'),

                ],
                'global'      => [
                    'search' => __('Search', 'codepress-admin-columns'),
                    'select' => __('Select', 'codepress-admin-columns'),
                ],
                'menu'        => [
                    'favorites' => __('Favorites', 'codepress-admin-columns'),
                ],
                'settings'    => [
                    'label' => [
                        'column'           => __('Column', 'codepress-admin-columns'),
                        'column_info'      => __('Column Info', 'codepress-admin-columns'),
                        'table_view_label' => __('Table View Label', 'codepress-admin-columns'),
                        'select_icon'      => __('Select Icon', 'codepress-admin-columns'),
                    ],
                ],
                'editor'      => [
                    'label'    => [
                        'save'                 => __('Save Changes', 'codepress-admin-columns'),
                        'add_column'           => __('Add Column', 'codepress-admin-columns'),
                        'add_columns'          => __('Add Columns', 'codepress-admin-columns'),
                        'clear_columns'        => __('Clear columns', 'codepress-admin-columns'),
                        'undo'                 => __('Undo', 'codepress-admin-columns'),
                        'load_default_columns' => __('Load default columns', 'codepress-admin-columns'),
                        'view'                 => __('View Table', 'codepress-admin-columns'),
                    ],
                    'sentence' => [
                        'columns_read_only'       => __(
                            sprintf(
                                'This table view is %s and can therefore not be edited.'
                                ,
                                sprintf(
                                    '<strong>%s</strong>',
                                    __('read only', 'codepress-admin-columns')
                                )
                            ),
                            'codepress-admin-columns'
                        ),
                        'column_no_duplicate'     => __(
                            'Column %s could not be duplicated',
                            'codepress-admin-columns'
                        ),
                        'original_already_exists' => __(
                            'Original column already exists.',
                            'codepress-admin-columns'
                        ),
                        'show_default_columns'    => __(
                            'The default columns will be shown on the list table when no columns are added.',
                            'codepress-admin-columns'
                        ),
                        'get_started'             => __(
                            'Start by adding columns to your list table.',
                            'codepress-admin-columns'
                        ),
                        'documentation'           => sprintf(
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
        return $this->table_screen_repository->find_all_by_ids(
            $this->favorite_repository->find_all(),
            new SortByLabel()
        );
    }

    private function encode_favorites(TableScreenCollection $collection): array
    {
        $keys = [];

        foreach ($collection as $table_screen) {
            $keys[] = (string)$table_screen->get_id();
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

}