<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PingStatus;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class PingStatusFactory extends ColumnFactory
{

    private $ping_status;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        PingStatus $ping_status
    ) {
        parent::__construct(
            $aggregate_formatter_builder_factory,
            $name_factory,
            $label_factory,
            $width_factory
        );

        $this->ping_status = $ping_status;
    }

    protected function get_label(): string
    {
        return __('Ping Status', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-ping_status';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return $this->aggregate_formatter_builder_factory
            ->create()
            ->add($this->ping_status);
    }

}