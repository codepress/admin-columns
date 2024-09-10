<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\IncludeMissingSizes;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AvailableSizes;

class AvailableSizesFactory extends BaseColumnFactory
{

    private $include_missing_sizes;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        IncludeMissingSizes $include_missing_sizes
    ) {
        parent::__construct($component_factory_registry);

        $this->include_missing_sizes = $include_missing_sizes;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->include_missing_sizes);
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    public function get_column_type(): string
    {
        return 'column-available_sizes';
    }

    public function get_label(): string
    {
        return __('Available Sizes', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new AvailableSizes((string)$config->get('include_missing_sizes') === '1'));
    }

}