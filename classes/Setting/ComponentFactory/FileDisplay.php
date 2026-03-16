<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\OptionCollection;

final class FileDisplay extends BaseComponentFactory
{

    private FileLink $file_link_factory;

    private FilePreviewSize $file_preview_size_factory;

    public function __construct(FileLink $file_link_factory, FilePreviewSize $file_preview_size_factory)
    {
        $this->file_link_factory = $file_link_factory;
        $this->file_preview_size_factory = $file_preview_size_factory;
    }

    protected function get_label(Config $config): ?string
    {
        return __('File Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return Input\OptionFactory::create_select(
            'file_display',
            OptionCollection::from_array([
                ''        => __('Filename', 'codepress-admin-columns'),
                'preview' => __('Preview', 'codepress-admin-columns'),
            ]),
            (string)$config->get('file_display', '')
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->file_link_factory->create(
                    $config,
                    StringComparisonSpecification::equal('')
                ),
                $this->file_preview_size_factory->create(
                    $config,
                    StringComparisonSpecification::equal('preview')
                ),
            ]),
            true
        );
    }

}
