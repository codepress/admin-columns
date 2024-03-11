<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

// TODO formatter
// TODO do we still want the extra description/tooltips as in the old version?
abstract class DateFormat implements ComponentFactory
{

    abstract protected function get_date_options(): OptionCollection;

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Date Format', 'codepress-admin-columns'))
            ->set_description(__('This will determine how the date will be displayed.', 'codepress-admin-columns'))
            ->set_input(
                new Custom('date_format')
            )
            ->set_children(
                new Children(new ComponentCollection([
                    new Component(
                        null,
                        null,
                        OptionFactory::create_radio(
                            'date_format',
                            $this->get_date_options(),
                            (string)$config->get('date_format')
                        )
                    ),
                ]))
            )
            ->set_formatter($this->create_formatter());

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function create_formatter(): Formatter
    {
        // TODO prepare the value to a timestamp
        // Then apply the format chosen in the setting
        return new Formatter\TimeStamp();
    }

}