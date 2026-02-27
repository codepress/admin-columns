<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\Excerpt;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class CaptionFactory extends BaseColumnFactory
{

    private StringLimit $string_limit;

    public function __construct(DefaultSettingsBuilder $default_settings_builder, StringLimit $string_limit)
    {
        parent::__construct(
            $default_settings_builder
        );
        $this->string_limit = $string_limit;
    }

    public function get_column_type(): string
    {
        return 'column-caption';
    }

    public function get_label(): string
    {
        return __('Caption', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return parent::get_settings($config)->add($this->string_limit->create($config));
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
            ->prepend(new Excerpt());
    }

}