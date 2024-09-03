<?php

namespace AC;

use AC\Type\TaxonomySlug;

interface Taxonomy
{

    // TODO check usages
    public function get_taxonomy(): TaxonomySlug;

}