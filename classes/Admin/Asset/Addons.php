<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Nonce;

class Addons extends Script
{

    private Nonce\Ajax $nonce;

    public function __construct(string $handle, Nonce\Ajax $nonce, Location $location)
    {
        parent::__construct($handle, $location, ['jquery']);

        $this->nonce = $nonce;
    }

    public function register(): void
    {
        parent::register();

        $translation = new Script\Localize\Translation([
            'plugin_installed' => __('The Add-on %s is activated.', 'codepress-admin-columns'),
        ]);

        $this->localize('ACi18n', $translation)
             ->add_inline_variable(
                 'AC',
                 [
                     $this->nonce->get_name() => $this->nonce->create(),
                     'is_network_admin'       => is_network_admin(),
                 ]
             );
    }

}