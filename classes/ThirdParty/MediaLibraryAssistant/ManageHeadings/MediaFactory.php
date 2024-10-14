<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\ManageHeadings;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageHeading\EncodeColumnsTrait;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;

class MediaFactory implements TableScreen\ManageHeadingFactory
{

    use EncodeColumnsTrait;

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof MediaLibraryAssistant\TableScreen;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        $headings = $this->encode_columns($list_screen);

        if ( ! $headings) {
            return null;
        }

        return new ScreenColumns(
            $headings
        );
    }
}