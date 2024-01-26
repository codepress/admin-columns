<?php

declare(strict_types=1);

namespace AC\Setting;

<<<<<<< HEAD
use AC\Setting\Component\Input;
use ACP\Expression\Specification;

interface Setting extends Component
=======
use AC\Expression\Specification;

// TODO Tobias can be removed, only used by AC\Settings\Column
// TODO David sometimes you want to configure a behaviour, the setting should expose this. e.g. update label
interface Setting
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
{

//    public function get_label(): string;
//
//    public function get_description(): string;

<<<<<<< HEAD
    public function get_input(): Input;
=======
    public function get_label(): string;

    public function get_description(): ?string;

    public function get_input(): ?Input;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2

    public function get_conditions(): Specification;

}