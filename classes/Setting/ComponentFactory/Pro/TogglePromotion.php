<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Pro;

use AC\Setting\ComponentFactory\Builder;
use AC\Setting\Config;
use AC\Setting\Control\Input;

class TogglePromotion extends Builder
{

    private string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    protected function get_label(Config $config): ?string
    {
        return $this->label;;
    }

    protected function get_input(Config $config): ?Input
    {
        return new Input\Custom('pro_feature', 'pro_feature', [

        ]);
    }

}