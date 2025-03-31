<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\IsLink;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class PermalinkFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after;

    private IsLink $is_link;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        BeforeAfter $before_after,
        IsLink $is_link
    ) {
        parent::__construct(
            $base_settings_builder
        );
        $this->before_after = $before_after;
        $this->is_link = $is_link;
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->is_link->create($config),
            $this->before_after->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-permalink';
    }

    public function get_label(): string
    {
        return __('Permalink', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Post\Permalink());

        if ($config->get('is_link') === 'on') {
            $formatters->add(new Formatter\Linkable());
        }

        $formatters->add(Formatter\BeforeAfter::create_from_config($config));
    }

}