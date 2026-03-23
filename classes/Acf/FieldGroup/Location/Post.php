<?php

declare(strict_types=1);

namespace AC\Acf\FieldGroup\Location;

use AC\Acf\FieldGroup;
use ACF_Location;

class Post implements FieldGroup\Query
{

    private string $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    /**
     * Enable all ACF location rules for the specified post type. Then call acf_get_field_groups() to get all
     * field groups that match the location rules.
     * @see acf_match_location_rule()
     */
    public function get_groups(): array
    {
        $rule_params = $this->get_rules_parameter();

        foreach ($rule_params as $rule_param) {
            add_filter('acf/location/rule_match/' . $rule_param, '__return_true', 16);
        }

        $groups = acf_get_field_groups(['post_type' => $this->post_type]);

        foreach ($rule_params as $rule_param) {
            remove_filter('acf/location/rule_match/' . $rule_param, '__return_true', 16);
        }

        return $groups;
    }

    /**
     * Retrieves all possible ACF location rules associated with the specified post type.
     */
    private function get_rules_parameter(): array
    {
        $black_list = ['post_type'];

        $params = [
            'user_type',
        ];

        $store = acf_get_store('location-types');

        if ( ! $store) {
            return $params;
        }

        foreach ($store->get_data() as $location_rule) {
            if ( ! $location_rule instanceof ACF_Location) {
                continue;
            }

            if (in_array($location_rule->name, $black_list, true)) {
                continue;
            }

            if ('post' !== $location_rule->object_type) {
                continue;
            }

            if ($location_rule->object_subtype && $this->post_type !== $location_rule->object_subtype) {
                continue;
            }

            $params[] = $location_rule->name;
        }

        return array_values(array_unique($params));
    }

}
