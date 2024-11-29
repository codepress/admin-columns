<?php

namespace AC\Admin\Asset\Script;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Localize\Translation;
use AC\Form\NonceFactory;

final class SettingsFactory
{

    public const HANDLE = 'ac-admin-page-settings';

    private $location;

    public function __construct(Location\Absolute $location)
    {
        $this->location = $location;
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
            'restore_settings_warning'     => __(
                "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop",
                'codepress-admin-columns'
            ),
        ];

        $nonce = (new NonceFactory())->create_ajax();

        $script = new Script(
            self::HANDLE,
            $this->location->with_suffix('assets/js/admin-page-settings.js'),
            [
                Script\GlobalTranslationFactory::HANDLE,
            ]
        );
        $script->localize('AC_SETTINGS_I18N', Translation::create($translations))
               ->add_inline_variable('AC_SETTINGS', [
                   $nonce->get_name() => $nonce->create(),
               ]);

        return $script;
    }

}