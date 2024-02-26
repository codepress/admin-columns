<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class TermProperty extends Settings\Control implements Formatter
{

    public const NAME = 'term';

    private $term_property;

    public function __construct(string $term_property, Specification $conditions = null)
    {
        $input = OptionFactory::create_select(
            'term_property',
            OptionCollection::from_array(
                [
                    ''     => __('Title'),
                    'slug' => __('Slug'),
                    'id'   => __('ID'),
                ]
            ),
            $term_property
        );

        parent::__construct(
            $input,
            __('Display', 'codepress-admin-columns'),
            null,
            $conditions
        );
        $this->term_property = $term_property;
    }

    // TODO test
    public function format(Value $value): Value
    {
        $term = get_term($value->get_id());

        if ( ! $term || is_wp_error($term)) {
            return $value;
        }

        switch ($this->term_property) {
            case 'slug' :
                return $value->with_value($term->slug);
            case 'id' :
                return $value->with_value($term->term_id);
            default :
                return $value->with_value($term->name);
        }
    }

}