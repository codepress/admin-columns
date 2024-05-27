<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;

class LinkablePostProperty extends PostProperty
{

    private $post_link;

    public function __construct(
        StringLimit $string_limit,
        ImageSize $image_size,
        UserProperty $user_property,
        PostStatusIcon $post_status_icon,
        Date $date,
        PostLink $post_link
    ) {
        parent::__construct($string_limit, $image_size, $user_property, $post_status_icon, $date);

        $this->post_link = $post_link;
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->post_link->create(
                    $config
                ),
            ])
        );
    }
}