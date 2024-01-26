<?php

namespace AC\Column;

use AC\Column;

// TODO obsolete?
abstract class Meta extends Column
{

    /**
     * Return the meta_key of this column
     * @return string
     */
    // TODO check usages
    abstract public function get_meta_key();

    public function get_raw_value($id)
    {
        return $this->get_meta_value($id, $this->get_meta_key());
    }

    public function get_meta_value($id, $meta_key, bool $single = true)
    {
        return get_metadata($this->get_meta_type(), $id, $meta_key, $single);
    }

}