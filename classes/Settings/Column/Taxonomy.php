<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Settings;

class Taxonomy extends Settings\Setting
{

    // TODO implement Formatter

    public function __construct(string $post_type = null, Specification $conditions = null)
    {
        $input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array(
                ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)
            )
        );

        parent::__construct(
            'taxonomy',
            __('Taxonomy', 'codepress-admin-columns'),
            null,
            $input,
            $conditions
        );
    }

    //    public function create_view()
    //    {
    //        $taxonomy = $this->create_element('select', 'taxonomy');
    //        $taxonomy->set_no_result(__('No taxonomies available.', 'codepress-admin-columns'))
    //                 ->set_options(ac_helper()->taxonomy->get_taxonomy_selection_options($this->get_post_type()))
    //                 ->set_attribute('data-label', 'update')
    //                 ->set_attribute('data-refresh', 'column');
    //
    //        return new View([
    //            'setting' => $taxonomy,
    //            'label'   => __('Taxonomy', 'codepress-admin-columns'),
    //        ]);
    //    }

}