<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PostTitle;
use AC\Value\Formatter\Wrapper;

class TitleRawFactory extends ColumnFactory
{

    private $character_limit_factory;

    private $post_link_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        CharacterLimit $character_limit_factory,
        PostLink $post_link_factory
    ) {
        parent::__construct($default_settings_builder);

        $this->character_limit_factory = $character_limit_factory;
        $this->post_link_factory = $post_link_factory;
    }

    public function get_column_type(): string
    {
        return 'column-title_raw';
    }

    public function get_label(): string
    {
        return __('Title Only', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->character_limit_factory->create($config),
            $this->post_link_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->prepend(new PostTitle())
                     ->add(new Wrapper('<span class="row-title">', '</span>'));
    }

}