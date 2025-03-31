<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class DescriptionFactory extends BaseColumnFactory
{

    private ComponentFactory\WordLimit $word_limit_factory;

    private ComponentFactory\BeforeAfter $before_after_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\WordLimit $word_limit_factory,
        ComponentFactory\BeforeAfter $before_after_factory
    ) {
        parent::__construct($base_settings_builder);

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
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\User\Meta('description'));

        return $formatters;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->word_limit_factory);
        $factories->add($this->before_after_factory);
    }

}