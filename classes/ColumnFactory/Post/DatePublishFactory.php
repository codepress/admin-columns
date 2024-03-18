<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\Post\DatePublishFormatted;
use AC\Setting\Formatter\Post\PostDate;

class DatePublishFactory extends ColumnFactory
{

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Date $date_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->add_component_factory($date_factory);
    }

    public function get_type(): string
    {
        return 'column-date_published';
    }

    protected function get_label(): string
    {
        return __('Date Published', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components): array
    {
        return array_merge([
            new DatePublishFormatted(),
        ], parent::get_formatters($components), [
            new PostDate(),
        ]);
    }

}