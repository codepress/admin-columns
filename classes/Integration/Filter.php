<?php

namespace AC\Integration;

use AC\Type\Integrations;

interface Filter
{

    public function filter(Integrations $integrations): Integrations;

}