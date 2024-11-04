<?php

namespace AC;

use AC\Type\TaxonomySlug;

interface Taxonomy
{

    public function get_taxonomy(): TaxonomySlug;

}