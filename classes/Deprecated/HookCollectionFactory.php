<?php

declare(strict_types=1);

namespace AC\Deprecated;

class HookCollectionFactory
{

    //            TODO replace these hooks that contain $column
    //            ac/column/audio_player/valid_mime_types
    //            ac/headings/label
    //            ac/list_screen/preferences
    //            ac/headings
    //            ac/column/video_player/valid_mime_types
    //            ac/column/settings/column_types
    //            ac/column_group
    //            ac/column/custom_field/use_text_input
    //            ac/column/custom_field/field_types

    public function create_filters(): HookCollection
    {
        return new HookCollection([
            new Hook('ac/column/value', '5.0', 'ac/v2/column/value'),
            new Hook('ac/column/value/sanitize', '5.0', 'ac/v2/column/value/sanitize'),
            new Hook('ac/column_types', '5.0', 'ac/v2/column_types'),
            new Hook('ac/column/audio_player/valid_mime_types', '5.0', 'ac/v2/column/audio_player/valid_mime_types'),

            // Removed
            new Hook('ac/column/separator', '5.0'),
            new Hook('ac/headings', '5.0'),
            new Hook('ac/column_group', '5.0'),
        ]);
    }

    public function create_actions(): HookCollection
    {
        return new HookCollection([
            new Hook('ac/ready', '5.0'),
        ]);
    }

}