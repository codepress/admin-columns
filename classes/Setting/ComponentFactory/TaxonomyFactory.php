<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Type\PostTypeSlug;

final class TaxonomyFactory
{

    public function create(PostTypeSlug $post_type): Taxonomy
    {
        return new Taxonomy($post_type);
    }

}