<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class IdFactory extends ColumnFactory
{

    protected $before_after_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->before_after_factory = $before_after_factory;
    }

    public function get_type(): string
    {
        return 'column-postid';
    }

    protected function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

}