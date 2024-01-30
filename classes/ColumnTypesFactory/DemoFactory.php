<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC\Column;
use AC\PostType;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\TableScreen;

// TODO Proof-of-concept POC
class DemoFactory
{

    public function create(TableScreen $table_screen, string $type, Config $config): ?Column
    {
        if ( ! $table_screen instanceof PostType) {
            return null;
        }

        switch ($type) {
            case 'column-excerpt':
                return new Column\Post\Excerpt(
                    new SettingCollection([
//                        (new Settings\Column\LabelFactory())->create($config),
//                        (new Settings\Column\WidthFactory())->create($config),
                        (new Settings\Column\StringLimitFactory())->create($config),
//                        (new Settings\Column\BeforeAfterFactory())->create($config),
                    ])
                );
            default:
                return null;
        }
    }

}