<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeBuilder;
use AC\Value\Formatter;

class DateConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'date';

    public function __construct(
        ComponentFactory\DateFormat\Date $date_format,
        ComponentFactory\DateSaveFormat $date_save_format
    ) {
        $this->date_format = $date_format;
        $this->date_save_format = $date_save_format;
    }

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Date', 'codepress-admin-columns'), 'basic')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $save_format = $config->get('date_save_format', '');
                    $date_formatter = $save_format
                        ? new Formatter\DateMapper($save_format, 'U')
                        : new Formatter\Timestamp();
                    $formatters->prepend($date_formatter);
                }
            )->add_child_component(
                $this->date_format,
                StringComparisonSpecification::equal(self::TYPE)
            )
            ->add_child_component(
                $this->date_save_format,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}