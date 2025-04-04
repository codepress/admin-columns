<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Post\FeaturedImageDisplay;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FeaturedImageFactory extends BaseColumnFactory
{

    private FeaturedImageDisplay $featured_image_component;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        FeaturedImageDisplay $featured_image_component
    ) {
        parent::__construct($default_settings_builder);

        $this->featured_image_component = $featured_image_component;
    }

    public function get_column_type(): string
    {
        return 'column-featured_image';
    }

    public function get_label(): string
    {
        return __('Featured Image', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->featured_image_component->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Formatter\Post\FeaturedImage());

        return $formatters;
    }

}