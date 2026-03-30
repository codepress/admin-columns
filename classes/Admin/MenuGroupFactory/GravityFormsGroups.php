<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;
use ACA;

class GravityFormsGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if ($table_screen instanceof ACA\GravityForms\TableScreen\Entry) {
            return new MenuGroup(
                'gravity_forms',
                sprintf('%s - %s', __('Gravity Forms'), __('Entries', 'codepress-admin-columns')),
                20
            );
        }

        return null;
    }

}
