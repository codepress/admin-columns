<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC;
use AC\FormatterCollection;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\Config;

class CountConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'count';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Number of Values', 'codepress-admin-columns'), 'multiple')
            ->add_formatter(
                self::TYPE,
                function (Config $config, FormatterCollection $formatters) {
                    $formatters->add(new AC\Formatter\ArrayToCollection());
                    $formatters->add(new AC\Formatter\Count());
                }
            );
    }
}
