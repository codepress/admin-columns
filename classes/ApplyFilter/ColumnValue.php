<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Setting\Context;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;

class ColumnValue
{

    private $id;

    private Context $context;

    private TableScreen $table_screem;

    private ListScreenId $list_id;

    public function __construct(Context $context, $id, TableScreen $table_screem, ListScreenId $list_id)
    {
        $this->id = $id;
        $this->context = $context;
        $this->table_screem = $table_screem;
        $this->list_id = $list_id;
    }

    public function apply_filter(Value $value): Value
    {
        $render = apply_filters(
            'ac/column/render',
            $value->get_value(),
            $this->context,
            $this->id,
            $this->table_screem,
            $this->list_id
        );

        if (is_scalar($render)) {
            $value = $value->with_value($render);
        }

        return $value;
    }

}