<?php

declare(strict_types=1);

namespace AC\Form;

class NonceFactory
{

    public function create_ajax(): Nonce
    {
        return new Nonce('ac-ajax', '_ajax_nonce');
    }

}