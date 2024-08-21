<?php

namespace AC\Column;

// TODO remove. Instead use ExtendedValue.
interface AjaxValue
{

    public function get_ajax_value($id): string;

}