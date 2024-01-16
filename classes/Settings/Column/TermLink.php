<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Expression\Specification;
use WP_Term;

class TermLink extends Settings\Column implements AC\Setting\Formatter
{

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'term_link_to';
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array($this->get_link_options()),
            'filter'
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

    protected function get_link_options()
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $link = null;

        switch ($options->get($this->name)) {
            case 'filter':
                $term = get_term($value->get_id());

                if ($term instanceof WP_Term) {
                    $link = ac_helper()->taxonomy->get_filter_by_term_url(
                        $term,
                        $this->column->get_post_type() ?: null
                    );
                }
                break;
            case 'edit' :
                $link = get_edit_term_link($value->get_id());

                break;
        }

        return $link
            ? $value->with_value(sprintf('<a href="%s">%s</a>', $link, $value))
            : $value;
    }

}