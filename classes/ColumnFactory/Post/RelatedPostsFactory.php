<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;

class RelatedPostsFactory extends BaseColumnFactory
{
    public function get_label(): string
    {
        return __('Related Posts', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-related_posts';
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            (new ComponentFactory\RelatedPostType())->create($config),
            $this->create_meta_key_component($config),
            (new ComponentFactory\RelatedPostsDisplay())->create($config),
        ]);
    }

    private function create_meta_key_component(Config $config): Component
    {
        return (new ComponentBuilder())
            ->set_label(__('Meta Key', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select_remote(
                    'related_meta_key',
                    'ac-custom-field-keys',
                    (string)$config->get('related_meta_key', ''),
                    [
                        'meta_type' => AC\MetaType::POST,
                        'post_type' => (string)$config->get('related_post_type', ''),
                    ],
                    __('Select', 'codepress-admin-columns')
                )
            )
            ->build();
    }

}
