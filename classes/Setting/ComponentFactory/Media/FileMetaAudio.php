<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Media;

use AC\Setting\ComponentFactory\Builder;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

final class FileMetaAudio extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Audio Meta');
    }

    protected function get_input(Config $config): ?Input
    {
        $options = $this->get_meta_options();
        $option = $options->first();

        return OptionFactory::create_select(
            'media_meta_key',
            $options,
            $config->get('media_meta_key', $option ? $option->get_value() : '')
        );
    }

    protected function get_meta_options(): OptionCollection
    {
        $types = [
            'bitrate'           => __('Bitrate', 'codepress-admin-columns'),
            'bitrate_mode'      => __('Bitrate Mode', 'codepress-admin-columns'),
            'channelmode'       => __('Channelmode', 'codepress-admin-columns'),
            'channels'          => __('Channels', 'codepress-admin-columns'),
            'compression_ratio' => __('Compression Ratio', 'codepress-admin-columns'),
            'created_timestamp' => __('Created Timestamp', 'codepress-admin-columns'),
            'dataformat'        => __('Data Format', 'codepress-admin-columns'),
            'encoder_options'   => __('Encoder Options', 'codepress-admin-columns'),
            'fileformat'        => __('Fileformat', 'codepress-admin-columns'),
            'filesize'          => __('Filesize', 'codepress-admin-columns'),
            'length'            => __('Length', 'codepress-admin-columns'),
            'length_formatted'  => __('Length Formatted', 'codepress-admin-columns'),
            'lossless'          => __('Losless', 'codepress-admin-columns'),
            'mime_type'         => __('Mime Type', 'codepress-admin-columns'),
            'sample_rate'       => __('Sample Rate', 'codepress-admin-columns'),
        ];

        natcasesort($types);

        return OptionCollection::from_array($types);
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($config->get('media_meta_key', '')) {
            case 'bitrate':
                $formatters->add(new Formatter\Media\Audio\Bitrate());
                break;
            case 'channels':
                $formatters->add(new Formatter\Media\Audio\Channels());
                break;

            case 'compression_ratio':
                $formatters->add(new Formatter\Media\NumberFormat(4));
                break;
            case 'created_timestamp':
                $formatters->add(
                    new Formatter\Date\DateFormat(get_option('date_format') . ' ' . get_option('time_format'))
                );
                break;
            case 'filesize':
                $formatters->add(new Formatter\Media\ReadableFileSize());
                break;

            case 'length':
                $formatters->add(new Formatter\Media\NumberFormat(0, '', ' sec'));
                break;
            case'sample_rate':
                $formatters->add(new Formatter\Media\NumberFormat(0, '', ' Hz'));
                break;
        }
    }

}