<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Pro;

use AC\Setting\ComponentFactory\BaseComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;

class TogglePromotion extends BaseComponentFactory
{

    private string $label;

    private string $feature;

    public function __construct(string $label, string $feature = '')
    {
        $this->label = $label;
        $this->feature = $feature;
    }

    protected function get_label(Config $config): ?string
    {
        return $this->label;
    }

    protected function get_input(Config $config): ?Input
    {
        return new Input\Custom('pro_feature', 'pro_feature', [
            'feature' => $this->feature,
        ]);
    }

}