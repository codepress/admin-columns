<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;

// TODO David sometimes you want to configure a behaviour, the setting should expose this. e.g. update label
interface Setting
{

    public function get_name(): string;

    public function get_label(): string;

    public function get_description(): string;

    public function get_input(): ?Input;

    public function get_conditions(): Specification;

}