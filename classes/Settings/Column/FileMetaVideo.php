<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Formatter;
use AC\Type\Value;

class FileMetaVideo extends FileMeta implements Formatter
{

    public function __construct(string $meta_key, Specification $specification = null)
    {
        $video_types = [
            'created_timestamp' => __('Created Timestamp', 'codepress-admin-columns'),
            'dataformat'        => __('Dataformat', 'codepress-admin-columns'),
            'fileformat'        => __('Fileformat', 'codepress-admin-columns'),
            'height'            => __('Height', 'codepress-admin-columns'),
            'length'            => __('Length', 'codepress-admin-columns'),
            'length_formatted'  => __('Length Formatted', 'codepress-admin-columns'),
            'width'             => __('Width', 'codepress-admin-columns'),
        ];

        natcasesort($video_types);

        $audio_types = [
            'audio.bits_per_sample' => __('Bits Per Sample', 'codepress-admin-columns'),
            'audio.channelmode'     => __('Channelmode', 'codepress-admin-columns'),
            'audio.channels'        => __('Channels', 'codepress-admin-columns'),
            'audio.codec'           => __('Codec', 'codepress-admin-columns'),
            'audio.dataformat'      => __('Dataformat', 'codepress-admin-columns'),
            'audio.lossless'        => __('Losless', 'codepress-admin-columns'),
            'audio.sample_rate'     => __('Sample Rate', 'codepress-admin-columns'),
        ];

        $audio_types = array_map([$this, 'wrap_audio_string'], $audio_types);

        natcasesort($audio_types);

        parent::__construct(
            __('Audio Meta', 'codepress-admin-columns'),
            array_merge($video_types, $audio_types),
            $meta_key,
            $specification
        );
    }

    private function format_suffix_number_format(Value $value, string $suffix): Value
    {
        return $value->with_value(
            sprintf('%s %s', number_format($value->get_value()), $suffix)
        );
    }

    // TODO Test formatting
    public function format(Value $value): Value
    {
        switch ($this->meta_key) {
            case 'height':
            case 'width':
                return $value->get_value() > 0
                    ? $this->format_suffix_number_format($value, __('px', 'codepress-admin-columns'))
                    : $value;
            case 'length':
                return $value->get_value() > 0
                    ? $this->format_suffix_number_format($value, __('sec', 'codepress-admin-columns'))
                    : $value;
            case 'audio.channels':
                return $value->get_value() > 0
                    ? $this->format_suffix_number_format(
                        $value,
                        _n('channels', 'channels', $value->get_value(), 'codepress-admin-columns')
                    )
                    : $value;

            case 'audio.sample_rate':
                return $value->get_value() > 0
                    ? $this->format_suffix_number_format($value, __('Hz', 'codepress-admin-columns'))
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

            default:
                return $value;
        }
    }

    private function wrap_audio_string($string): string
    {
        return sprintf('%s (%s)', $string, __('audio', 'codepress-admin-columns'));
    }

}