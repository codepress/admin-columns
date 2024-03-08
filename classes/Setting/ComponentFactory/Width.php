<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class Width implements ComponentFactory
{

    private const WIDTH = 'width';
    private const UNIT = 'width_unit';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $unit_options = [
            '%',
            'px',
            'auto',
        ];

        $unit = $config->get(self::UNIT, $unit_options[0]);
        $width = (int)$config->get(self::WIDTH);

        return (new ComponentBuilder())
            ->set_label(
                __('Width', 'codepress-admin-columns')
            )
            ->set_input(
                OptionFactory::create_select(
                    self::UNIT,
                    OptionCollection::from_array(
                        $unit_options,
                        false
                    ),
                    $unit
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        new Component(
                            null,
                            null,
                            Number::create_single_step(
                                self::WIDTH,
                                0,
                                100,
                                $width
                            ),
                            StringComparisonSpecification::equal($unit_options[0])
                        ),
                        new Component(
                            null,
                            null,
                            Number::create_single_step(
                                self::WIDTH,
                                0,
                                null,
                                $width
                            ),
                            StringComparisonSpecification::equal($unit_options[1])
                        ),
                    ])
                )
            )
            ->set_type('width')
            ->build();
    }
}