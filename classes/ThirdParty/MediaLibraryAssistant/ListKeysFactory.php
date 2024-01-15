<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\ListKeyCollection;
use AC\Type\ListKey;

class ListKeysFactory implements AC\ListKeysFactory
{

    public function create(): ListKeyCollection
    {
        return new ListKeyCollection([
            new ListKey('mla-media-assistant'),
        ]);
    }

}