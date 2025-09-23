<?php

declare(strict_types=1);

namespace AC\Form;

class PreviewNonce extends Nonce
{

    public function __construct()
    {
        parent::__construct('ac-preview-mode', 'acp_nonce');
    }
}