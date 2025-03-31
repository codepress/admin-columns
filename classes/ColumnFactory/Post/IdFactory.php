<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\Config;

class IdFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->before_after_factory = $before_after_factory;
    }

    public function get_column_type(): string
    {
        return 'column-postid';
    }

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->before_after_factory->create($config),
        ]);
    }

}