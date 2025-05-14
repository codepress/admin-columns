<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type;

final class ListScreenType implements Filter
{

    private Type\ListScreenType $type;

    public function __construct(Type\ListScreenType $type)
    {
        $this->type = $type;
    }

    public static function create_default()
    {
        return new self(Type\ListScreenType::create_default());
    }

    public static function create_template_type(): self
    {
        return new self(Type\ListScreenType::create_template());
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($this->type->equals($list_screen->get_type())) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}