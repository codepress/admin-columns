<?php

namespace AC\Admin\Asset\Script;

use AC\AdminColumns;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Localize\Translation;
use AC\Form\NonceFactory;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

final class SettingsFactory
{

    public const HANDLE = 'ac-admin-page-settings';

    private Location $location;
    private bool $is_pro_active;

    public function __construct(AdminColumns $plugins, bool $is_pro_active)
    {
        $this->location = $plugins->get_location();
        $this->is_pro_active = $is_pro_active;
    }

    private function get_upgrade_features(): array
    {
        return [
            [
                'label'   => __('Sortable Columns', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Any column can be turned into an ordered list.', 'codepress-admin-columns'),
                    __('Sort on numbers, names, prices or anything else.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Multiple Table Views', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s %s',
                    __('Need certain columns for certain tasks?', 'codepress-admin-columns'),
                    __('Create multiple columns presets.', 'codepress-admin-columns'),
                    __('Switching between sets is easy and can be done from any list table.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Bulk Editing', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Need to update multiple values at once?', 'codepress-admin-columns'),
                    __('With Bulk Edit you can update thousands of values at once.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Multisite Support', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Get insights about your entire network of sites.', 'codepress-admin-columns'),
                    __('And of course, all network sites can use Admin Columns.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('All integration add-ons', 'codepress-admin-columns'),
                'tooltip' => __('Get access to all our integrations for popular WordPress plugins', 'codepress-admin-columns'),
            ],
            [
                'label'   => __('Smart Filters', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Only show the content that matches the rules you set.', 'codepress-admin-columns'),
                    __('Filters help you to get valuable insights about orders, users, posts, anything really.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Horizontal Scrolling', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Need more columns than the size of your screen?', 'codepress-admin-columns'),
                    __('Simply scroll through your columns horizontally so your overview can be complete and functional.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Customize List Table', 'codepress-admin-columns'),
                'tooltip' => __('Create a better user experience when visiting the list table by setting preferences and hiding the functionality you do not need.', 'codepress-admin-columns'),
            ],
            [
                'label'   => __('Add Row', 'codepress-admin-columns'),
                'tooltip' => __('Insert a post, user or anything really directly from the overview.', 'codepress-admin-columns'),
            ],
            [
                'label'   => __('Sticky Headers', 'codepress-admin-columns'),
                'tooltip' => __('Keep your column names on top of the screen when scrolling down the page', 'codepress-admin-columns'),
            ],
            [
                'label'   => __('Inline Editing', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('No need to open your post, page, order, user or anything else.', 'codepress-admin-columns'),
                    __('Edit your content directly from the overview.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Export Table to CSV', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Want to import content in Excel, Mailchimp or anything else?', 'codepress-admin-columns'),
                    __('Admin Columns can generate a fully customized and downloadable CSV.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Taxonomy Support', 'codepress-admin-columns'),
                'tooltip' => __('Manage columns for your Taxonomy overview pages', 'codepress-admin-columns'),
            ],
            [
                'label'   => __('Conditional Formatting', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Highlight important data based on conditions.', 'codepress-admin-columns'),
                    __('Instantly spot trends, issues or key values in your overview.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Table Views', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Save and reuse specific table states including filters, sorting and columns.', 'codepress-admin-columns'),
                    __('Quickly switch between different workflows without reconfiguring your table.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('File Storage', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Save column settings to PHP files on your file system.', 'codepress-admin-columns'),
                    __('Easily ship setups across environments and manage them in version control.', 'codepress-admin-columns')
                ),
            ],
            [
                'label'   => __('Column Metrics', 'codepress-admin-columns'),
                'tooltip' => sprintf(
                    '%s %s',
                    __('Get instant insights with totals, counts and sums at the bottom of your columns.', 'codepress-admin-columns'),
                    __('Quickly analyze your data without exporting or running separate reports.', 'codepress-admin-columns')
                ),
            ],
        ];
    }

    public function create(): Script
    {
        $translations = [
            'settings'                     => __('Settings', 'codepress-admin-columns'),
            'general_settings'             => __('General Settings', 'codepress-admin-columns'),
            'general_settings_description' => __('These settings affect the list table.', 'codepress-admin-columns'),

            'show_x_button' => __("Show %s button on table screen.", 'codepress-admin-columns'),
            'edit_button'   => __('Edit columns', 'codepress-admin-columns'),

            'restore_settings'             => __('Restore settings', 'codepress-admin-columns'),
            'restore_settings_description' => __(
                'Delete all column settings and restore the default settings.',
                'codepress-admin-columns'
            ),
            'settings_saved_successful'    => __('Settings saved successfully.', 'codepress-admin-columns'),
            'restore_settings_warning'     => __(
                "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop",
                'codepress-admin-columns'
            ),
        ];

        $nonce = NonceFactory::create_ajax();

        $upgrade_url = (new UtmTags(Site::create_admin_columns_pro(), 'settings'))->get_url();

        $script = new Script(
            self::HANDLE,
            $this->location->with_suffix('assets/js/admin-page-settings.js'),
            [
                Script\GlobalTranslationFactory::HANDLE,
            ]
        );
        $script
            ->localize('ac_settings_i18n', Translation::create($translations))
            ->add_inline_variable('ac_settings', [
                $nonce->get_name() => $nonce->create(),
                'is_pro'          => $this->is_pro_active,
                'upgrade_panel'   => $this->is_pro_active ? null : [
                    'upgrade_url'  => $upgrade_url,
                    'subtitle'     => __('Upgrade to Admin Columns Pro and unlock all the awesome features.', 'codepress-admin-columns'),
                    'view_all'     => __('View all Pro features', 'codepress-admin-columns'),
                    'button'       => __('Upgrade to Admin Columns Pro', 'codepress-admin-columns'),
                    'features'     => $this->get_upgrade_features(),
                ],
            ]);

        return $script;
    }

}