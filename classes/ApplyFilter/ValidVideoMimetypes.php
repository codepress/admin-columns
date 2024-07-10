<?php

namespace AC\ApplyFilter;

class ValidVideoMimetypes
{

    public function apply_filters(): array
    {
        return (array)apply_filters(
            'ac/column/video_player/valid_mime_types',
            [
                'video/mp4',
                'video/webm',
                'video/quicktime',
            ]
        );
    }

}