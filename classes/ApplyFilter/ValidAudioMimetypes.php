<?php

namespace AC\ApplyFilter;

use AC;

class ValidAudioMimetypes
{

    private $column;

    public function __construct(AC\Column $column)
    {
        $this->column = $column;
    }

    public function apply_filters(): array
    {
        return (array)apply_filters(
            'ac/column/video_player/valid_mime_types',
            [
                'audio/mpeg',
                'audio/flac',
                'audio/wav',
            ],
            $this->column
        );
    }

}