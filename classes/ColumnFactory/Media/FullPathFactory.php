<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PathScope;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentUrl;

class FullPathFactory extends BaseColumnFactory
{

    private PathScope $path_scope;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        PathScope $path_scope
    ) {
        parent::__construct($base_settings_builder);

        $this->path_scope = $path_scope;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->path_scope->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-full_path';
    }

    public function get_label(): string
    {
        return __('File Path', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new AttachmentUrl());

        return $formatters;
    }

}