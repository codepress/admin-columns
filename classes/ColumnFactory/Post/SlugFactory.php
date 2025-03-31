<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Slug;

class SlugFactory extends BaseColumnFactory
{

    private $character_limit;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        CharacterLimit $character_limit
    ) {
        parent::__construct($base_settings_builder);

        $this->character_limit = $character_limit;
    }

    public function get_label(): string
    {
        return __('Slug', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-slug';
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->character_limit);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Slug());

        return $formatters;
    }

}