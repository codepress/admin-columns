<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class ExcerptFactory extends BaseColumnFactory
{

    private ComponentFactory\StringLimit $string_limit;

    private ComponentFactory\BeforeAfter $before_after;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\StringLimit $string_limit,
        ComponentFactory\BeforeAfter $before_after
    ) {
        parent::__construct($base_settings_builder);

        $this->string_limit = $string_limit;
        $this->before_after = $before_after;
    }

    public function get_column_type(): string
    {
        return 'column-excerpt';
    }

    public function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->string_limit->create($config),
            $this->before_after->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->prepend(new Formatter\Post\ContentExcerpt())
                     ->add(new Formatter\Post\ExcerptIcon());
    }

}