<?php

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class ExcerptFactory extends BaseColumnFactory
{

    private StringLimit $string_limit;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        StringLimit $string_limit
    ) {
        parent::__construct($base_settings_builder);

        $this->string_limit = $string_limit;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->string_limit->create($config),
        ]);
    }

    public function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-excerpt';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new AC\Value\Formatter\Comment\Property('comment_content'));

        return $formatters;
    }

}