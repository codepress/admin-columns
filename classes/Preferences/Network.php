<?php

namespace AC\Preferences;

// TODO remove
class Network extends Site
{

    protected function get_key(): string
    {
        return 'network_' . parent::get_key();
    }

}