<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\OptionCollection;

final class FileDisplay extends BaseComponentFactory
{

    private FileLink $file_link_factory;

    public function __construct(FileLink $file_link_factory)
    {
        $this->file_link_factory = $file_link_factory;
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
        $width = $config->has('file_preview_w') ? (int)$config->get('file_preview_w') : 80;
        $height = $config->has('file_preview_h') ? (int)$config->get('file_preview_h') : 80;

        return new Children(
            new ComponentCollection([
                $this->file_link_factory->create(
                    $config,
                    StringComparisonSpecification::equal('')
                ),
                new Component(
                    __('Width', 'codepress-admin-columns'),
                    null,
                    Number::create_single_step('file_preview_w', 0, null, $width),
                    StringComparisonSpecification::equal('preview')
                ),
                new Component(
                    __('Height', 'codepress-admin-columns'),
                    null,
                    Number::create_single_step('file_preview_h', 0, null, $height),
                    StringComparisonSpecification::equal('preview')
                ),
            ]),
            true
        );
    }

}
