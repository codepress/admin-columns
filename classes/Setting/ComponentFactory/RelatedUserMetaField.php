<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\MetaType;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Type\TableScreenContext;

class RelatedUserMetaField extends CustomField
{

    private FieldType $field_type;

    public function __construct(FieldType $field_type)
    {
        parent::__construct(new TableScreenContext(MetaType::create_user_type()));

        $this->field_type = $field_type;
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->field_type->create($config),
            ])
        );
    }

}