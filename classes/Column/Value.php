<?php

namespace AC\Column;

use AC\Setting\Config;

interface Value
{

    public function renderable(Config $options): Renderable;

}