<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class PostCountFactory extends BaseColumnFactory
{

    private ComponentFactory\PostTypeFactory $post_type_factory;

    private ComponentFactory\PostStatus $post_status;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostTypeFactory $post_type_factory
    ) {
        parent::__construct($default_settings_builder);

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
        return parent::get_formatters($config)
                     ->add(
                         new AC\Formatter\User\PostCount(
                             $this->get_post_types($config) ?: [],
                             $config->get('post_status') ?: []
                         )
                     );
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
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