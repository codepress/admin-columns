<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\IsLink;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class PermalinkFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after;

    private IsLink $is_link;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        BeforeAfter $before_after,
        IsLink $is_link
    ) {
        parent::__construct(
            $default_settings_builder
        );
        $this->before_after = $before_after;
        $this->is_link = $is_link;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\Post\Permalink());

        if ($config->get('is_link') === 'on') {
            $formatters->add(new AC\Formatter\Linkable());
        }

        $formatters->add(\AC\Formatter\BeforeAfter::create_from_config($config));

        return $formatters;
    }

}