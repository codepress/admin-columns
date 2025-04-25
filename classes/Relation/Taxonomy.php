<?php

namespace AC\Relation;

use AC\Relation;

class Taxonomy extends Relation
{

    /**
     * @var object
     */
    private $taxonomy;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->taxonomy = get_taxonomy($this->get_id());
    }

    public function get_type()
    {
        return 'taxonomy';
    }

    public function get_taxonomy()
    {
        return $this->taxonomy;
    }

    public function get_labels()
    {
        if ( ! $this->taxonomy) {
            return false;
        }

        return $this->taxonomy->labels;
    }

}