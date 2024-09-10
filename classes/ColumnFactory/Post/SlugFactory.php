<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Slug;

class SlugFactory extends BaseColumnFactory
{

    private $character_limit;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        CharacterLimit $character_limit
    ) {
        parent::__construct($component_factory_registry);

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
        parent::add_component_factories($factories);

        $factories->add($this->character_limit);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Slug());
    }

}