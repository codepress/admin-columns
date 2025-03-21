<?php

declare(strict_types=1);

namespace AC\Deprecated;

class HookCollectionFactory
{

    //            TODO replace these hooks that contain $column
    //            TODO Move pro to pro class?
    //            ac/column/audio_player/valid_mime_types
    //            ac/headings/label
    //            ac/list_screen/preferences
    //            ac/headings
    //            ac/column/video_player/valid_mime_types
    //            ac/column/settings/column_types
    //            ac/column_group

    public function create_filters(): HookCollection
    {
        return new HookCollection([
            // Replaced
            new Hook('ac/column/value', '5.0', 'ac/v2/column/value'),
            new Hook('ac/column/value/sanitize', '5.0', 'ac/v2/column/value/sanitize'),
            new Hook('ac/column_types', '5.0', 'ac/v2/column_types'),
            new Hook('ac/column/audio_player/valid_mime_types', '5.0', 'ac/v2/column/audio_player/valid_mime_types'),

            // Replaced Pro
            new Hook('ac/export/value', '7.0', 'acp/v2/export/value'),
            new Hook('ac/export/value/escape', '7.0', 'acp/v2/export/value/escape'),

            // Removed
            new Hook('ac/column/separator', '5.0'),
            new Hook('ac/headings', '5.0'),
            new Hook('ac/column_group', '5.0'),
            new Hook('ac/column/custom_field/field_types', '5.0'),

            // Removed Pro
            new Hook('ac/export/column/disable', '7.0'),
        ]);
    }

    public function create_actions(): HookCollection
    {
        return new HookCollection([
            new Hook('ac/ready', '5.0'),
        ]);
    }

}