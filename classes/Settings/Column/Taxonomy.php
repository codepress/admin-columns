<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use ACP\Expression\Specification;

class Taxonomy extends Settings\Column
{

    /**
     * @var string
     */
    private $taxonomy;

    private $post_type;

    public function __construct(AC\Column $column, string $post_type = null, Specification $conditions = null)
    {
        $this->post_type = $post_type ?: $column->get_post_type();
        $this->name = 'taxonomy';
        $this->label = __('Taxonomy', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array(
                ac_helper()->taxonomy->get_taxonomy_selection_options($this->post_type)
            )
        );

        parent::__construct($column, $conditions);
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