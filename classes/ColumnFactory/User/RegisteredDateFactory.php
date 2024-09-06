<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class RegisteredDateFactory extends BaseColumnFactory
{

    private $date_format;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Date $date_format
    ) {
        parent::__construct($component_factory_registry);
        $this->date_format = $date_format;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);
        $this->add_component_factory($this->date_format);
    }

    public function get_label(): string
    {
        return __('Registered', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_registered';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Property('user_registered'));
        $formatters->add(new Formatter\Timestamp());

        return parent::get_formatters($components, $config, $formatters);
    }
}