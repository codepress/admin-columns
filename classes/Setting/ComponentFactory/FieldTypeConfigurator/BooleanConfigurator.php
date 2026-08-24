<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Formatter;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class BooleanConfigurator implements FieldTypeConfigurator
{
    private const TYPE = 'checkmark';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('True / False', 'codepress-admin-columns'), 'choice')
            ->add_formatter(self::TYPE, function (Setting\Config $config, FormatterCollection $formatters) {
                $formatters->add(new Formatter\YesNoIcon());
            });
    }
}
