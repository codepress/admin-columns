<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\PathScope;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentUrl;

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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->path_scope);
    }

    public function get_column_type(): string
    {
        return 'column-full_path';
    }

    public function get_label(): string
    {
        return __('File Path', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AttachmentUrl());
    }

}