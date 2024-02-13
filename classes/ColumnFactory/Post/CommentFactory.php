<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\StringLimitFactory;
use AC\Settings\Column\WidthFactory;

class CommentFactory extends ColumnFactory
{

    private $string_limit_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        StringLimitFactory $string_limit_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->string_limit_factory = $string_limit_factory;
    }

    public function get_type(): string
    {
        return 'column-comment_count';
    }

    protected function get_label(): string
    {
        return __('Comment Count', 'codepress-admin-columns');
    }

    //    public function create(Config $config): Column
    //    {
    //        $settings = $this->builder_factory->create()
    //                                          ->add_defaults()
    //                                          ->add(new CommentsFactory())
    //                                          ->add($this->string_limit_factory)
    //                                          ->build($config);
    //
    //        return new Column(
    //            'column-comment_count',
    //            __('Comment Count', 'codepress-admin-columns'),
    //            Aggregate::from_settings($settings),
    //            $settings
    //        );
    //    }

}