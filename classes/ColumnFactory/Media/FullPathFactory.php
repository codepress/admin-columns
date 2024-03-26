<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PathScope;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AttachmentUrl;
use AC\Setting\FormatterCollection;

class FullPathFactory extends BaseColumnFactory
{
    private $path_scope;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        PathScope $path_scope
    ) {
        parent::__construct($component_factory_registry);

        $this->path_scope = $path_scope;
    }

    protected function add_component_factories(): void
    {
        $this->add_component_factory($this->path_scope);

        parent::add_component_factories();
    }

    public function get_type(): string
    {
        return 'column-full_path';
    }

    protected function get_label(): string
    {
        return __('File Path', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentUrl());

        return parent::get_formatters($components, $config, $formatters);
    }

}