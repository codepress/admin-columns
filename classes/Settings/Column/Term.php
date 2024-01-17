<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class Term extends Settings\Column
    implements Settings\FormatValue
{

    // TODO
    public const NAME = 'term';

    /**
     * @var string
     */
    private $term_property;

    protected function set_name()
    {
        $this->name = self::NAME;
    }

    protected function define_options()
    {
        return ['term_property'];
    }

    public function create_view()
    {
        $setting = $this
            ->create_element('select')
            ->set_options([
                ''     => __('Title'),
                'slug' => __('Slug'),
                'id'   => __('ID'),
            ]);

        $view = new View([
            'label'   => __('Display', 'codepress-admin-columns'),
            'setting' => $setting,
        ]);

        return $view;
    }

    /**
     * @return string
     */
    public function get_term_property()
    {
        return $this->term_property;
    }

    /**
     * @param string $term_property
     *
     * @return bool
     */
    public function set_term_property($term_property)
    {
        $this->term_property = $term_property;

        return true;
    }

    public function format($value, $original_value)
    {
        $term = $value;

        if (is_numeric($term)) {
            $term = get_term($term);
        }

        if ( ! $term || is_wp_error($term)) {
            return $value;
        }

        switch ($this->get_term_property()) {
            case 'slug' :
                return $term->slug;
            case 'id' :
                return $term->term_id;
            default :
                return $term->name;
        }
    }

}