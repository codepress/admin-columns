<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Id;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class IdFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        BeforeAfter $before_after
    ) {
        parent::__construct(
            $default_settings_builder
        );
        $this->before_after = $before_after;
    }

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-comment_id';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->with_formatter(new Id());
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->before_after->create($config),
        ]);
    }

}