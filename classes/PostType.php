<?php

namespace AC;

use AC\Type\PostTypeSlug;

interface PostType
{

    // TODO check usages
    public function get_post_type(): PostTypeSlug;

}