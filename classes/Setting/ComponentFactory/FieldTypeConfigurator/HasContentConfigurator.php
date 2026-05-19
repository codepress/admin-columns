<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Formatter;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class HasContentConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'has_content';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Has Content', 'codepress-admin-columns'), 'choice')
            ->add_formatter(self::TYPE, function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                $formatters->add(new Formatter\HasValue());
                $formatters->add(new Formatter\YesNoIcon());
            });
    }
}