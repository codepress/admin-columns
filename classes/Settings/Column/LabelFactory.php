<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Sanitize\Kses;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class LabelFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        $label = $config->has('label')
            ? (string)$config->get('label')
            : '';

        $label = (string)apply_filters(
            'ac/column/label',
            (new Kses())->sanitize($label),
            $label
        );

        return new Label($label);
    }

}