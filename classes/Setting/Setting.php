<?php

declare(strict_types=1);

namespace AC\Setting;

// TODO David defaults
// TODO David value from settings
interface Setting
{

    public function get_name(): string;

    public function get_label(): string;

    public function get_description(): string;

    public function get_input(): Input;

    public function has_conditions(): bool;

    public function get_conditions(): ConditionCollection;

}