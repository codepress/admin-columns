<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Component\AttributeCollection;

interface Component
{

    public function get_type(): string;

    public function get_attributes(): AttributeCollection;

}