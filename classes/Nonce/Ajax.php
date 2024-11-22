<?php

namespace AC\Nonce;

use AC\Form\Nonce;

class Ajax extends Nonce
{

    public function __construct()
    {
        parent::__construct('ac-ajax', '_ajax_nonce');
    }

}