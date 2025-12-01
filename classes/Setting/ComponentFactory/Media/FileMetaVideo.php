<?php

namespace AC\Setting\ComponentFactory\Media;

use AC\Setting\ComponentFactory\BaseComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FileMetaVideo extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Video Meta');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'media_meta_key',
            $this->get_meta_options(),
            $config->get('media_meta_key', '')
        );
    }

    protected function get_meta_options(): OptionCollection
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

        $types = array_merge($video_types, $audio_types);

        return OptionCollection::from_array($types);
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($config->get('media_meta_key', '')) {
            case 'height':
            case 'width':
                $formatters->add(new Formatter\Suffix('px'));
                break;
            case 'length':
                $formatters->add(new Formatter\Suffix('sec'));
                break;
            case 'audio.channels':
                $formatters->add(new Formatter\Suffix('channels'));
                break;
            case 'audio.sample_rate':
                $formatters->add(new Formatter\Suffix('Hz'));
                break;
            case 'created_timestamp':
                $formatters->add(
                    new Formatter\Date\DateFormat(get_option('date_format') . ' ' . get_option('time_format'))
                );
                break;
        }
    }

    private function wrap_audio_string($string): string
    {
        return sprintf('%s (%s)', $string, __('audio', 'codepress-admin-columns'));
    }
}