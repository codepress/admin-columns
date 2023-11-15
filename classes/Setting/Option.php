<?php

declare(strict_types=1);

namespace AC\Setting;

interface Option
{

    public function get_options(): OptionCollection;

}