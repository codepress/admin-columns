<?php

namespace AC\Column\Media;

abstract class MetaValue extends Meta
{

    abstract protected function get_option_name();

    public function get_raw_value($id)
    {
        $value = parent::get_raw_value($id);

        $option = $this->get_option_name();

        if (empty($value[$option])) {
            return false;
        }

        return $value[$option];
    }

}