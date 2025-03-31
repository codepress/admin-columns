<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\Posts;
use AC\Value\Formatter;

class PostCountFactory extends BaseColumnFactory
{

    private ComponentFactory\PostTypeFactory $post_type_factory;

    private ComponentFactory\PostStatus $post_status;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostTypeFactory $post_type_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->post_status = $post_status;
        $this->post_type_factory = $post_type_factory;
    }

    public function get_label(): string
    {
        return __('Post Count', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_postcount';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(
            new Formatter\User\PostCount(
                new Posts($this->get_post_types($config), $config->get('post_status'))
            )
        );

        return $formatters;
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->post_type_factory->create(true)->create($config),
            $this->post_status->create($config),
        ]);
    }

    private function get_post_types(Config $config): ?array
    {
        $post_type = $config->get('post_type');

        if (in_array($post_type, ['any', ''], true)) {
            return null;
        }

        return [$post_type];
    }

}