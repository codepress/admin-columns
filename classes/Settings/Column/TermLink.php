<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\Type\Value;
use AC\Settings;
use WP_Term;

class TermLink extends Settings\Column implements AC\Setting\Formatter
{

    private $post_type;

    public function __construct(string $post_type = null, Specification $conditions = null)
    {
        $input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array($this->get_link_options()),
            'filter'
        );

        parent::__construct(
            'term_link_to',
            __('Link To', 'codepress-admin-columns'),
            null,
            $input,
            $conditions
        );
        $this->post_type = $post_type;
    }

    protected function get_link_options(): array
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

    public function format(Value $value, Config $options): Value
    {
        $link = null;

        switch ($options->get($this->name)) {
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