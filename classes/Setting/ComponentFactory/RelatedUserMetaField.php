<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Type\ListKey;

class RelatedUserMetaField extends CustomField
{

    private $field_type;

    public function __construct(FieldType $field_type)
    {
        parent::__construct(new ListKey('wp-users'));

        $this->field_type = $field_type;
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                // TODO huge performance issue
                $this->field_type->create($config),
            ])
        );
    }

}