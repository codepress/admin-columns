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
        $upgrade_url = new UtmTags(Site::create_admin_columns_pro(), 'settings');

        $items = [
            'sortable-columns'     => __('Sortable Columns', 'codepress-admin-columns'),
            'multiple-views'       => __('Multiple Table Views', 'codepress-admin-columns'),
            'bulk-editing'         => __('Bulk Editing', 'codepress-admin-columns'),
            'multisite'            => __('Multisite Support', 'codepress-admin-columns'),
            'integrations'         => __('All integration add-ons', 'codepress-admin-columns'),
            'smart-filters'        => __('Smart Filters', 'codepress-admin-columns'),
            'unlimited-columns'    => __('Unlimited Columns', 'codepress-admin-columns'),
            'horizontal-scrolling' => __('Horizontal Scrolling', 'codepress-admin-columns'),
            'customize-list-table' => __('Customize List Table', 'codepress-admin-columns'),
            'add-row'              => __('Add Row', 'codepress-admin-columns'),
            'sticky-headers'       => __('Sticky Headers', 'codepress-admin-columns'),
            'inline-editing'       => __('Inline Editing', 'codepress-admin-columns'),
            'export'               => __('Export Table to CSV', 'codepress-admin-columns'),
            'taxonomy'             => __('Taxonomy Support', 'codepress-admin-columns'),
        ];

        $features = [];

        foreach ($items as $utm_content => $label) {
            $features[] = [
                'url'   => $upgrade_url->with_content($utm_content)->get_url(),
                'label' => $label,
            ];
        }

        return $features;
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

            'upgrade_to_pro_subtitle' => __(
                'Upgrade to Admin Columns Pro and unlock all the awesome features.',
                'codepress-admin-columns'
            ),
            'view_all_features'       => __('View all Pro features', 'codepress-admin-columns'),
            'upgrade_button'          => __('Upgrade to Admin Columns Pro', 'codepress-admin-columns'),
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
                'is_pro'       => $this->is_pro_active,
                'upgrade_url'  => $upgrade_url,
                'features'     => $this->is_pro_active ? [] : $this->get_upgrade_features(),
            ]);

        return $script;
    }

}