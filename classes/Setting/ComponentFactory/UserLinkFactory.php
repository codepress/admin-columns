<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\ComponentFactory;
use AC\Type\PostTypeSlug;

final class UserLinkFactory
{

    public function create(PostTypeSlug $post_type = null): ComponentFactory
    {
        return new UserLink($post_type);
    }

}