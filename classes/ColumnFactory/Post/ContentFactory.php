<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PostContent;
use AC\Value\Formatter\StripTags;

class ContentFactory extends BaseColumnFactory
{

    private StringLimit $string_limit_factory;

    private BeforeAfter $before_after_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        StringLimit $string_limit_factory,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->string_limit_factory = $string_limit_factory;
        $this->before_after_factory = $before_after_factory;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->string_limit_factory->create($config),
            $this->before_after_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new PostContent(),
            new StripTags(),
        ]);

        return $formatters->merge(parent::get_formatters($config));
    }

    public function get_column_type(): string
    {
        return 'column-content';
    }

    public function get_label(): string
    {
        return __('Content', 'codepress-admin-columns');
    }

}