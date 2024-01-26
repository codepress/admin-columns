<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Component\Input;
use ACP\Expression\Specification;

interface Setting extends Component
{

//    public function get_label(): string;
//
//    public function get_description(): string;

    public function get_input(): Input;

    public function get_conditions(): Specification;

}