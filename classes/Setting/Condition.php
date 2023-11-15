<?php

declare(strict_types=1);

namespace AC\Setting;

interface Condition
{

    public function get_conditions(): ConditionCollection;

}