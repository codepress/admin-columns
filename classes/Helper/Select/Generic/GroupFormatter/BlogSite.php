<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic\GroupFormatter;

use AC\Helper\Select\Generic\GroupFormatter;

class BlogSite implements GroupFormatter
{

    public function format(string $value): string
    {
        foreach ($this->get_groups() as $key => $label) {
            if (strpos($value, $key) === 0) {
                return $label;
            }
        }

        return __('Default', 'codepress-admin-columns');
    }

    private function get_groups(): array
    {
        global $wpdb;

        static $groups;

        if (null === $groups) {
            $groups = [];

            foreach (get_sites() as $site) {
                $label = sprintf(
                    '%s %s',
                    __('Network Site:', 'codepress-admin-columns'),
                    ac_helper()->network->get_site_option($site->blog_id, 'blogname')
                );

                if (get_current_blog_id() === $site->blog_id) {
                    $label = sprintf('%s (%s)', $label, __('current', 'codepress-admin-columns'));
                }

                $groups[$wpdb->get_blog_prefix($site->blog_id)] = $label;
            }

            $groups = array_reverse($groups);
        }

        return $groups;
    }

}