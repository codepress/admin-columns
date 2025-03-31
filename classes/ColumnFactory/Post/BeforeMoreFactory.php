<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\WordLimit;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\BeforeMoreContent;

final class BeforeMoreFactory extends BaseColumnFactory
{

    private WordLimit $word_limit_factory;

    public function __construct(BaseSettingsBuilder $base_settings_builder, WordLimit $word_limit_factory)
    {
        parent::__construct($base_settings_builder);

        $this->word_limit_factory = $word_limit_factory;
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->word_limit_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new BeforeMoreContent());

        return $formatters;
    }

    public function get_column_type(): string
    {
        return 'column-before_moretag';
    }

    public function get_label(): string
    {
        return __('More Tag', 'codepress-admin-columns');
    }

}