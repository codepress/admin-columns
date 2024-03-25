<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\DatePublishFormatted;
use AC\Setting\Formatter\Post\PostDate;
use AC\Setting\FormatterCollection;

class DatePublishFactory extends ColumnFactory
{

    private $date_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Date $date_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->date_factory = $date_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->date_factory);
    }

    public function get_type(): string
    {
        return 'column-date_published';
    }

    protected function get_label(): string
    {
        return __('Date Published', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PostDate());
        $formatters = parent::get_formatters($components, $config, $formatters);
        $formatters->add(new DatePublishFormatted());

        return $formatters;
    }

}