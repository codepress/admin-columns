<?php

declare(strict_types=1);

namespace AC\Setting;

// TODO David conditionals vs subsettings
// TODO David extra description "settings" text only?
// TODO conditionals/ dependent settings based on values
interface Setting
{

    public function get_name(): string;

    public function get_label(): string;

    public function get_description(): string;

    public function get_input(): Input;

}