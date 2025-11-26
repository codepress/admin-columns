<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeBuilder;
use AC\Value\Formatter\Color;

class ColorConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'color';

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Color', 'codepress-admin-columns'), 'basic')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters): void {
                    $formatters->add(new Color());
                }
            );
    }
}