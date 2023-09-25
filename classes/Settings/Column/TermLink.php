<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;
use WP_Term;

class TermLink extends Settings\Column
    implements Settings\FormatValue
{

    /**
     * @var string
     */
    protected $term_link_to;

    protected function define_options()
    {
        return [
            'term_link_to' => 'filter',
        ];
    }

    public function create_view()
    {
        $select = $this->create_element('select')->set_options($this->get_link_options());

        $view = new View([
            'label'   => __('Link To', 'codepress-admin-columns'),
            'setting' => $select,
        ]);

        return $view;
    }

    protected function get_link_options()
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

    /**
     * @return string
     */
    public function get_term_link_to()
    {
        return $this->term_link_to;
    }

    /**
     * @param string $term_link_to
     *
     * @return bool
     */
    public function set_term_link_to($term_link_to)
    {
        $this->term_link_to = $term_link_to;

        return true;
    }

    public function format($value, $original_value)
    {
        $link = false;

        switch ($this->get_term_link_to()) {
            case 'filter':
                $term = get_term($original_value);

                if ($term instanceof WP_Term) {
                    $link = ac_helper()->taxonomy->get_filter_by_term_url(
                        $term,
                        $this->column->get_post_type() ?: null
                    );
                }
                break;
            case 'edit' :
                $link = get_edit_term_link($original_value);

                break;
        }

        if ($link) {
            return sprintf('<a href="%s">%s</a>', $link, $value);
        }

        return $value;
    }

}