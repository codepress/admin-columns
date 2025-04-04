<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\Download;

class DownloadFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-download';
    }

    public function get_label(): string
    {
        return __('Download', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Download());

        return $formatters;
    }

}