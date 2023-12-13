<?php

namespace AC;

class ColumnGroups
{

    public static function get_groups(): Groups
    {
        $groups = new Groups();

        $groups->add('default', __('Default', 'codepress-admin-columns'));
        $groups->add('plugin', __('Plugins'), 20);
        $groups->add('custom_field', __('Custom Fields', 'codepress-admin-columns'), 30);
        $groups->add('media', __('Media', 'codepress-admin-columns'), 32);
        $groups->add('media-meta', __('Meta', 'codepress-admin-columns'), 32);
        $groups->add('media-meta', __('Meta', 'codepress-admin-columns'), 32);
        $groups->add('media-image', __('Image', 'codepress-admin-columns'), 33);
        $groups->add('media-video', __('Video', 'codepress-admin-columns'), 34);
        $groups->add('media-audio', __('Audio', 'codepress-admin-columns'), 35);
        $groups->add('media-document', __('Document', 'codepress-admin-columns'), 35);
        $groups->add('media-file', __('File', 'codepress-admin-columns'), 35);
        $groups->add('custom', __('Custom', 'codepress-admin-columns'), 40);

        do_action('ac/column_groups', $groups);

        return $groups;
    }

}