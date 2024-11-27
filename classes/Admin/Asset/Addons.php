<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Nonce;

class Addons extends Script
{

    private Nonce\Ajax $nonce;

    private Location $asset_location;

    private bool $is_pro;

    public function __construct(
        string $handle,
        Nonce\Ajax $nonce,
        Location $location,
        Location $asset_location,
        bool $is_pro
    ) {
        parent::__construct($handle, $location, ['jquery']);

        $this->nonce = $nonce;
        $this->asset_location = $asset_location;
        $this->location = $location;
        $this->is_pro = $is_pro;
    }

    public function register(): void
    {
        parent::register();

        $translation = new Script\Localize\Translation([
            'plugin_installed' => __('The Add-on %s is activated.', 'codepress-admin-columns'),
        ]);

        $this->localize('ACi18n', $translation)
             ->add_inline_variable(
                 'AC_ADDONS',
                 [
                     $this->nonce->get_name() => $this->nonce->create(),
                     'is_network_admin'       => is_network_admin(),
                     'asset_location'         => $this->asset_location->get_url(),
                     'pro_installed'          => $this->is_pro,
                 ]
             );
    }

}