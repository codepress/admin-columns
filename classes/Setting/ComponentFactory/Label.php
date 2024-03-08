<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Sanitize\Kses;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class Label implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $label = $config->has('label')
            ? (string)$config->get('label')
            : '';

        $label = (string)apply_filters(
            'ac/column/label',
            (new Kses())->sanitize($label),
            $label
        );

        return new Component(
            __('Label', 'codepress-admin-columns'),
            __('This is the name which will appear as the column header.', 'codepress-admin-columns'),
            new Input\Custom(
                'label',
                'label',
                [],
                $label
            ),
            $conditions
        );
    }

}