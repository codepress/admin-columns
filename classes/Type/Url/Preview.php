<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Form\PreviewNonce;
use AC\Type\Uri;

class Preview extends Uri
{

    public function __construct(Uri $url, ?string $tab = null)
    {
        parent::__construct((string)$url);

        $nonce = new PreviewNonce();

        $this->add($nonce->get_name(), $nonce->create());
        $this->add('ac_action', 'ac-preview-mode');

        if ($tab) {
            $this->add('source_tab', $tab);
        }
    }

}