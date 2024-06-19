<?php

namespace AC\Asset\Script;

use AC\Asset\Location\Absolute;
use AC\Asset\Script;

class GlobalTranslationFactory
{

    public const HANDLE = 'ac-global-translations';

    private $location;

    private $translation;

    public function __construct(Absolute $location, Script\Localize\Translation $translation)
    {
        $this->location = $location;
        $this->translation = $translation;
    }

    public function create(): Script
    {
        $script = new Script(self::HANDLE, $this->location->with_suffix('assets/js/global-translations.js'));
        $script->localize('ac_global_translations', $this->translation);

        return $script;
    }

}