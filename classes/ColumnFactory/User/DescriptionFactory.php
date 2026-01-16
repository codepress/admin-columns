<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Value\Formatter;

class DescriptionFactory extends BaseColumnFactory
{

    private ComponentFactory\WordLimit $word_limit_factory;

    private ComponentFactory\BeforeAfter $before_after_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ComponentFactory\WordLimit $word_limit_factory,
        ComponentFactory\BeforeAfter $before_after_factory
    ) {
        parent::__construct($default_settings_builder);

        $this->word_limit_factory = $word_limit_factory;
        $this->before_after_factory = $before_after_factory;
    }

    public function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_description';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->prepend(new \AC\Formatter\User\Meta('description'));
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->word_limit_factory->create($config),
            $this->before_after_factory->create($config),
        ]);
    }

}