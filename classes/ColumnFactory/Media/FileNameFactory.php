<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\FileName;

class FileNameFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-file_name';
    }

    public function get_label(): string
    {
        return __('Filename', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new FileName());

        return parent::get_formatters($components, $config, $formatters);
    }
}