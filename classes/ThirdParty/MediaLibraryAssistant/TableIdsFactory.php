<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Type\TableId;
use AC\Type\TableIdCollection;

class TableIdsFactory implements AC\TableIdsFactory
{

    public function create(): TableIdCollection
    {
        return new TableIdCollection([
            new TableId('mla-media-assistant'),
        ]);
    }

}