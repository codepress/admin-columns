<?php

namespace AC\ApplyFilter;

class ValidAudioMimetypes
{

    public function apply_filters(): array
    {
        return (array)apply_filters(
            'ac/column/audio_mime_types',
            [
                'audio/mpeg',
                'audio/flac',
                'audio/wav',
            ]
        );
    }

}