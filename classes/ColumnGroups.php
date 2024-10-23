<?php

namespace AC;

use AC\Type\Group;
use AC\Type\Groups;

class ColumnGroups
{

    private function all(): Groups
    {
        static $groups = null;

        if (null === $groups) {
            $groups = new Groups();

            $groups->add(new Group('default', __('Default', 'codepress-admin-columns')));
            $groups->add(new Group('plugin', __('Plugins'), 20));
            $groups->add(new Group('custom_field', __('Custom Fields', 'codepress-admin-columns'), 30));
            $groups->add(new Group('media', __('Media', 'codepress-admin-columns'), 32));
            $groups->add(new Group('media-meta', __('Meta', 'codepress-admin-columns'), 32));
            $groups->add(new Group('media-meta', __('Meta', 'codepress-admin-columns'), 32));
            $groups->add(new Group('media-image', __('Image', 'codepress-admin-columns'), 33));
            $groups->add(new Group('media-video', __('Video', 'codepress-admin-columns'), 34));
            $groups->add(new Group('media-audio', __('Audio', 'codepress-admin-columns'), 35));
            $groups->add(new Group('media-document', __('Document', 'codepress-admin-columns'), 35));
            $groups->add(new Group('media-file', __('File', 'codepress-admin-columns'), 35));
            $groups->add(new Group('custom', __('Custom', 'codepress-admin-columns'), 40));

            do_action('ac/column_groups', $groups);
        }

        return $groups;
    }

    public function find(string $slug): ?Group
    {
        foreach ($this->all() as $group) {
            if ($group->get_slug() === $slug) {
                return $group;
            }
        }

        return null;
    }

    public function find_all(): Groups
    {
        return $this->all();
    }

}