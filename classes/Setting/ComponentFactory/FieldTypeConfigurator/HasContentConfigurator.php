<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Formatter;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class HasContentConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'has_content';

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Has Content', 'codepress-admin-columns'), 'choice')
            ->add_formatter(self::TYPE, function (Setting\Config $config, FormatterCollection $formatters) {
                $formatters->add(new Formatter\HasValue());
                $formatters->add(new Formatter\YesNoIcon());
            });
    }
}
