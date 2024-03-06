<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use WP_Term;

class TermLink extends Settings\Control implements AC\Setting\Formatter
{

    private $post_type;

    private $term_link_to;

    public function __construct(string $term_link_to, string $post_type = null, Specification $conditions = null)
    {
        $input = OptionFactory::create_select(
            'term_link_to',
            OptionCollection::from_array($this->get_link_options()),
            $term_link_to
        );

        parent::__construct(
            $input,
            __('Link To', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->post_type = $post_type;
        $this->term_link_to = $term_link_to;
    }

    protected function get_link_options(): array
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

    public function format(Value $value): Value
    {
        $link = null;

        switch ($this->term_link_to) {
            case 'filter':
                $term = get_term($value->get_id());

                if ($term instanceof WP_Term) {
                    $link = ac_helper()->taxonomy->get_filter_by_term_url(
                        $term,
                        $this->post_type
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