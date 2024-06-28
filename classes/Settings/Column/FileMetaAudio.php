<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Type\Value;
use AC\Value\Formatter;

class FileMetaAudio extends FileMeta implements Setting\Formatter
{

    public function __construct(string $meta_key, Specification $specification = null)
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

        parent::__construct(__('Audio Meta', 'codepress-admin-columns'), $types, $meta_key, $specification);
    }

    public function format(Value $value): Value
    {
        switch ($this->meta_key) {
            case 'bitrate':
                return (new Formatter\Media\Audio\Bitrate())->format($value);
            case 'channels':
                return (new Formatter\Media\Audio\Channels())->format($value);
            case 'compression_ratio':
                return $value->get_value() > 0
                    ? $value->with_value(number_format($value->get_value(), 4))
                    : $value;
            case 'created_timestamp':
                return $value->get_value()
                    ? $value->with_value(
                        ac_helper()->date->format_date(
                            sprintf('%s %s', get_option('date_format'), get_option('time_format')),
                            $value->get_value()
                        )
                    )
                    : $value;
            case 'filesize':
                return $value->with_value(ac_helper()->file->get_readable_filesize((int)$value->get_value()));
            case 'length':
                return $value->get_value() > 0
                    ? $value->with_value(
                        sprintf('%s %s', number_format($value->get_value()), __('sec', 'codepress-admin-columns'))
                    )
                    : $value;
            case 'sample_rate':
                return $value->get_value() > 0
                    ? $value->with_value(
                        sprintf('%s %s', number_format($value->get_value()), __('Hz', 'codepress-admin-columns'))
                    )
                    : $value;
            default:
                return $value;
        }
    }

}