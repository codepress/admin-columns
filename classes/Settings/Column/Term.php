<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class Term extends Settings\Setting implements Formatter
{

    public const NAME = 'term';

    public function __construct(Specification $conditions = null)
    {
        $input = Input\Option\Single::create_select(
            OptionCollection::from_array(
                [
                    ''     => __('Title'),
                    'slug' => __('Slug'),
                    'id'   => __('ID'),
                ]
            ),
            ''
        );

        parent::__construct(
            'term_property',
            __('Display', 'codepress-admin-columns'),
            null,
            $input,
            $conditions
        );
    }

    // TODO test
    public function format(Value $value, Config $options): Value
    {
        $term = $value->get_value();

        if (is_numeric($term)) {
            $term = get_term($term);
        }

        if ( ! $term || is_wp_error($term)) {
            return $value;
        }

        switch ($options->get('term_property')) {
            case 'slug' :
                return $term->slug;
            case 'id' :
                return $term->term_id;
            default :
                return $term->name;
        }
    }

}