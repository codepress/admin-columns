<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class Width extends Builder
{

    private const OPTION_PERCENT = '%';
    private const OPTION_PIXELS = 'px';

    private const OPTION_AUTO = 'auto';

    protected function get_label(Config $config): ?string
    {
        return __('Width', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): Input
    {
        $name = 'width_unit';

        return OptionFactory::create_select(
            $name,
            OptionCollection::from_array(
                [
                    self::OPTION_PERCENT,
                    self::OPTION_PIXELS,
                    self::OPTION_AUTO,
                ],
                false
            ),
            $config->get($name, self::OPTION_PERCENT)
        );
    }

    protected function get_children(Config $config): ?Children
    {
        $name = 'width';
        $value = $config->get($name);

        return new Children(
            new ComponentCollection([
                new Component(
                    null,
                    null,
                    Number::create_single_step(
                        $name,
                        0,
                        100,
                        $value ? (int)$value : null
                    ),
                    StringComparisonSpecification::equal(self::OPTION_PERCENT)
                ),
                new Component(
                    null,
                    null,
                    Number::create_single_step(
                        $name,
                        0,
                        null,
                        $value ? (int)$value : null
                    ),
                    StringComparisonSpecification::equal(self::OPTION_PIXELS)
                ),
            ])
        );
    }

    protected function get_type(Config $config): ?string
    {
        return 'width';
    }

}