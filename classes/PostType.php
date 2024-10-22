<?php

namespace AC;

use AC\Type\PostTypeSlug;

interface PostType
{

    public function get_post_type(): PostTypeSlug;

}