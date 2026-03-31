<?php

declare(strict_types=1);

namespace AC\Integration\SiteContext;

class AcfSiteContext
{

    public function get_context(): ?array
    {
        if ( ! function_exists('acf_get_field_groups')) {
            return null;
        }

        $field_groups = acf_get_field_groups();

        if (empty($field_groups)) {
            return null;
        }

        $post_types = [];

        foreach ($field_groups as $field_group) {
            if (empty($field_group['location'])) {
                continue;
            }

            foreach ($field_group['location'] as $group) {
                foreach ($group as $rule) {
                    if ('post_type' === $rule['param'] && '==' === $rule['operator']) {
                        $post_types[$rule['value']] = true;
                    }
                }
            }
        }

        return [
            'field_group_count' => count($field_groups),
            'post_type_count'   => count($post_types),
        ];
    }

}
