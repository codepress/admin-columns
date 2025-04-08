<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\TableIdCollection;
use AC\Type\TableId;

class TableIdsFactory implements AC\TableIdsFactory
{

    public function create(): TableIdCollection
    {
        return new TableIdCollection([
            new TableId('mla-media-assistant'),
        ]);
    }

}