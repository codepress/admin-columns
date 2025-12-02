<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\RenderFactory;
use AC\TableScreen;
use AC\TableScreen\ManageValueService;
use AC\TableScreen\ManageValueServiceFactory;
use InvalidArgumentException;

class MediaServiceFactory implements ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Media;
    }

    public function create(
        TableScreen $table_screen,
        RenderFactory $factory,
        int $priority = 100
    ): ManageValueService {
        if ( ! $table_screen instanceof TableScreen\Media) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Media($factory, $priority);
    }

}