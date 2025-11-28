<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeBuilder;
use AC\Value;

class BooleanConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'checkmark';

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('True / False', 'codepress-admin-columns'), 'choice')
            ->add_formatter(self::TYPE, function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                $formatters->add(new Value\Formatter\YesNoIcon());
            });
    }
}