<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\DatePublishFormatted;
use AC\Value\Formatter\Post\PostDate;

class DatePublishFactory extends BaseColumnFactory
{

    private $date_factory;

    public function __construct(ComponentFactoryRegistry $component_factory_registry, Date $date_factory)
    {
        parent::__construct($component_factory_registry);

        $this->date_factory = $date_factory;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        parent::add_component_factories($factories);
        
        $factories->add($this->date_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new PostDate());
        $formatters->add(new DatePublishFormatted());
    }

    public function get_column_type(): string
    {
        return 'column-date_published';
    }

    public function get_label(): string
    {
        return __('Date Published', 'codepress-admin-columns');
    }

}