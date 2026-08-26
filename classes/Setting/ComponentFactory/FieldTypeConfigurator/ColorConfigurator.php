<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Formatter\Color;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class ColorConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'color';

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Color', 'codepress-admin-columns'), 'basic')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters): void {
                    $formatters->add(new Color());
                }
            );
    }
}
