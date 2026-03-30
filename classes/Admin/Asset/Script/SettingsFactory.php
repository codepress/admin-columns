<?php

namespace AC\Admin\Asset\Script;

use AC\AdminColumns;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Localize\Translation;
use AC\Form\NonceFactory;
use AC\Type\StartingPrice;
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

    private function get_feature_groups(): array
    {
        return [
            [
                'title'    => __('Edit faster', 'codepress-admin-columns'),
                'features' => [
                    [
                        'label'   => __('Inline editing', 'codepress-admin-columns'),
                        'tooltip' => __('Edit any value directly from the list table - no need to open individual items.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Bulk editing', 'codepress-admin-columns'),
                        'tooltip' => __('Update hundreds of values at once. Select rows, pick a column, and apply changes in bulk.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Add rows from the table', 'codepress-admin-columns'),
                        'tooltip' => __('Create new posts, users, or other content without leaving the list table.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Conditional formatting', 'codepress-admin-columns'),
                        'tooltip' => __('Apply color rules to cells so you can instantly spot trends, deadlines, or issues in your data.', 'codepress-admin-columns'),
                    ],
                ],
            ],
            [
                'title'    => __('Find anything instantly', 'codepress-admin-columns'),
                'features' => [
                    [
                        'label'   => __('Smart filters', 'codepress-admin-columns'),
                        'tooltip' => __('Narrow down your list with advanced filter rules - combine conditions to find exactly what you need.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Sortable columns', 'codepress-admin-columns'),
                        'tooltip' => __('Click any column header to sort your list by that value - works with dates, numbers, text, and more.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Sticky headers', 'codepress-admin-columns'),
                        'tooltip' => __('Column headers stay visible as you scroll, so you always know what you are looking at.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Column metrics', 'codepress-admin-columns'),
                        'tooltip' => __('See totals, averages, and counts at the bottom of your columns for quick data insights.', 'codepress-admin-columns'),
                    ],
                ],
            ],
            [
                'title'    => __('Work your way', 'codepress-admin-columns'),
                'features' => [
                    [
                        'label'   => __('Multiple table views', 'codepress-admin-columns'),
                        'tooltip' => __('Create different column layouts for different tasks and switch between them with one click.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Export table to CSV', 'codepress-admin-columns'),
                        'tooltip' => __('Download your current view as a CSV file - ready for Excel, Google Sheets, or any other tool.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Horizontal scrolling', 'codepress-admin-columns'),
                        'tooltip' => __('Display as many columns as you need - scroll sideways to see them all without losing row context.', 'codepress-admin-columns'),
                    ],
                    [
                        'label'   => __('Integration add-ons', 'codepress-admin-columns'),
                        'tooltip' => __('Unlock columns for popular plugins like ACF, WooCommerce, Yoast SEO, and more.', 'codepress-admin-columns'),
                    ],
                ],
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
                'assets'           => $this->location->with_suffix('assets')->get_url(),
                'is_pro'           => $this->is_pro_active,
                'upgrade_panel'    => $this->is_pro_active
                    ? null
                    : [
                        'upgrade_url'    => $upgrade_url,
                        'badge'          => __('Admin Columns Pro', 'codepress-admin-columns'),
                        'title'          => __('Work faster in your list tables', 'codepress-admin-columns'),
                        'subtitle'       => __(
                            'Turn your list tables into a powerful workflow tool for editing, filtering, sorting, and exporting content.',
                            'codepress-admin-columns'
                        ),
                        'button'         => sprintf(
                            '%s - %s',
                            __('Upgrade', 'codepress-admin-columns'),
                            sprintf(
                            /* translators: %s: price (e.g. $79) */
                                __('from %s/year', 'codepress-admin-columns'),
                                StartingPrice::get()
                            )
                        ),
                        'view_all'       => __('See all Pro features', 'codepress-admin-columns'),
                        'trust'          => sprintf(
                            '%s · %s',
                            sprintf(
                            /* translators: %s: number of sites (e.g. 250,000+) */
                                __('%s sites', 'codepress-admin-columns'),
                                '250,000+'
                            ),
                            __('30-day money-back guarantee', 'codepress-admin-columns')
                        ),
                        'feature_groups' => $this->get_feature_groups(),
                    ],
            ]);

        return $script;
    }

}