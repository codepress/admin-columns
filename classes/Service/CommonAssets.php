<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Registerable;

class CommonAssets implements Registerable
{

    private $location;

    private $translation_factory;

    public function __construct(AdminColumns $plugin, Script\GlobalTranslationFactory $translation_factory)
    {
        $this->location = $plugin->get_location();
        $this->translation_factory = $translation_factory;
    }

    public function register(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'register_global_assets'], 1);
    }

    public function register_global_assets()
    {
        $this->translation_factory->create();
        (new Style('ac-utilities', $this->location->with_suffix('assets/css/utilities.css')))->register();
        (new Style('ac-ui', $this->location->with_suffix('assets/css/acui.css')))->register();
    }

}