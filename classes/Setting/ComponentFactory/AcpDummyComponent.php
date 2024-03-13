<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Aggregate;

final class AcpDummyComponent implements ComponentFactory
{

    private $comment_display;

    private $separator;

    public function __construct(CommentDisplay $comment_display, Separator $separator)
    {
        $this->comment_display = $comment_display;

        $this->separator = $separator;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Dummy Select', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'dummy_select',
                    OptionCollection::from_array([
                        'single'   => 'Single',
                        'multiple' => 'Multiple',
                    ]),
                    (string)$config->get('dummy_select')
                )
            )
            ->set_formatter($this->get_formatter($config))
            ->set_children(
            // Use same
                new Children(new ComponentCollection([
                    $this->comment_display->create($config),
                    $this->separator->create($config, StringComparisonSpecification::equal('multiple')),
                ]))
            );

        return $builder->build();
    }

    private function get_formatter(Config $config): Formatter
    {
        if ('single' === $config->get('dummy_select')) {
            // Get single Value with Comment ID
        }

        if ('multiple' === $config->get('dummy_select')) {
            // Get collection Value with Comment ID

        }

        return new Aggregate();
    }

}