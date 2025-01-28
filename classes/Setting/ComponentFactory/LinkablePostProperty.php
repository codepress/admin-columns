<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;

class LinkablePostProperty extends PostProperty
{

    private PostLink $post_link;

    private UserLink $user_link;

    public function __construct(
        StringLimit $string_limit,
        ImageSize $image_size,
        UserProperty $user_property,
        PostStatusIcon $post_status_icon,
        Date $date,
        PostLink $post_link,
        UserLink $user_link
    ) {
        parent::__construct($string_limit, $image_size, $user_property, $post_status_icon, $date);

        $this->post_link = $post_link;
        $this->user_link = $user_link;
    }

    protected function get_children(Config $config): ?Children
    {
        $children = parent::get_children($config);

        $components = $children->get_iterator();

        $components->add(
            $this->user_link->create(
                $config,
                StringComparisonSpecification::equal(self::PROPERTY_AUTHOR)
            )
        );
        $components->add(
            $this->post_link->create(
                $config,
                StringComparisonSpecification::equal(self::PROPERTY_TITLE)
            )
        );

        return $children;
    }
}