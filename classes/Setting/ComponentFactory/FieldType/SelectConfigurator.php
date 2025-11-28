<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeBuilder;

class SelectConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'select';

    private Setting\ComponentFactory\SelectOptions $select_options;

    public function __construct(Setting\ComponentFactory\SelectOptions $select_options)
    {
        $this->select_options = $select_options;
    }

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Select', 'codepress-admin-columns'), 'choice')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    if ($config->get('is_multiple', 'off') === 'on') {
                        $formatters->add(new AC\Value\Formatter\ArrayToCollection());
                    }
                    $formatters->add(new AC\Value\Formatter\SelectOptionMapper($config));
                }
            )->add_child_component(
                $this->select_options,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}