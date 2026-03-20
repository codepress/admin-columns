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

    private function get_feature_groups(): array
    {
        return [
            [
                'title'    => __('Edit faster', 'codepress-admin-columns'),
                'features' => [
                    __('Inline editing', 'codepress-admin-columns'),
                    __('Bulk editing', 'codepress-admin-columns'),
                    __('Add rows from the table', 'codepress-admin-columns'),
                    __('Conditional formatting', 'codepress-admin-columns'),
                ],
            ],
            [
                'title'    => __('Find anything instantly', 'codepress-admin-columns'),
                'features' => [
                    __('Smart filters', 'codepress-admin-columns'),
                    __('Sortable columns', 'codepress-admin-columns'),
                    __('Sticky headers', 'codepress-admin-columns'),
                    __('Column metrics', 'codepress-admin-columns'),
                ],
            ],
            [
                'title'    => __('Work your way', 'codepress-admin-columns'),
                'features' => [
                    __('Multiple table views', 'codepress-admin-columns'),
                    __('Export table to CSV', 'codepress-admin-columns'),
                    __('Horizontal scrolling', 'codepress-admin-columns'),
                    __('Integration add-ons', 'codepress-admin-columns'),
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
                            'Turn your list tables into a powerful workflow tool for editing, filtering, sorting, and exporting content - without jumping between screens.',
                            'codepress-admin-columns'
                        ),
                        'button'         => sprintf(
                            '%s — %s',
                            __('Upgrade', 'codepress-admin-columns'),
                            sprintf(
                            /* translators: %s: price (e.g. €79) */
                                __('from %s/year', 'codepress-admin-columns'),
                                '€79'
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