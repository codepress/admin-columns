<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use WP_User;

class UserAssigned implements Filter
{

    private $user;

    public function __construct(WP_User $user)
    {
        $this->user = $user;
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($list_screen->is_user_assigned($this->user)) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}