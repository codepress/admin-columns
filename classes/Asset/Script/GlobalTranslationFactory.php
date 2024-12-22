<?php

namespace AC\Asset\Script;

use AC\AdminColumns;
use AC\Asset\Script;
use AC\Asset\Script\Localize\Translation;

class GlobalTranslationFactory
{

    public const HANDLE = 'ac-global-translations';

    private AdminColumns $plugin;

    private Translation $translation;

    public function __construct(AdminColumns $plugin, Translation $translation)
    {
        $this->plugin = $plugin;
        $this->translation = $translation;
    }

    public function create(): Script
    {
        $script = new Script(
            self::HANDLE,
            $this->plugin->get_location()->with_suffix('assets/js/global-translations.js')
        );
        $script->localize('ac_global_translations', $this->translation);

        return $script;
    }

}