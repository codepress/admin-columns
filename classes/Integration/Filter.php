<?php

namespace AC\Integration;

use AC\Integrations;

interface Filter
{

    public function filter(Integrations $integrations): Integrations;

}