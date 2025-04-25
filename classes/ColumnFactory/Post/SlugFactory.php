<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Slug;

class SlugFactory extends BaseColumnFactory
{

    private $character_limit;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        CharacterLimit $character_limit
    ) {
        parent::__construct($default_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->character_limit->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Slug());

        return $formatters;
    }

}