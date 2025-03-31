<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;

class IdFactory extends BaseColumnFactory
{

    private $before_after_factory;

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

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->before_after_factory->create($config),
        ]);
    }

}