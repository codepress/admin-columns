<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Control\OptionCollection;
use AC\Settings;

class Taxonomy extends Settings\Control
{

    // TODO implement Formatter

    public function __construct(string $taxonomy = null, string $post_type = null, Specification $conditions = null)
    {
        $input = AC\Setting\Control\Input\OptionFactory::create_select(
            'taxonomy',
            OptionCollection::from_array(
                ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)
            ),
            $taxonomy
        );

        parent::__construct(
            $input,
            __('Taxonomy', 'codepress-admin-columns'),
            null,
            $conditions
        );
    }

}