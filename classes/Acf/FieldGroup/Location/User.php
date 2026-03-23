<?php

declare(strict_types=1);

namespace AC\Acf\FieldGroup\Location;

use AC\Acf\FieldGroup\Query;

class User implements Query
{

    public function get_groups(): array
    {
        add_filter('acf/location/rule_match/user_type', '__return_true', 16);
        add_filter('acf/location/rule_match/page_type', '__return_true', 16);

        add_filter('acf/location/rule_match/user_form', '__return_true', 16);
        add_filter('acf/location/rule_match/user_role', '__return_true', 16);

        // We need to pass an argument, otherwise the filters won't work
        $groups = acf_get_field_groups(['ac_dummy' => true]);

        // Remove all location filters for the next storage_model
        remove_filter('acf/location/rule_match/user_type', '__return_true', 16);
        remove_filter('acf/location/rule_match/page_type', '__return_true', 16);

        remove_filter('acf/location/rule_match/user_form', '__return_true', 16);
        remove_filter('acf/location/rule_match/user_role', '__return_true', 16);

        return $groups;
    }

}
