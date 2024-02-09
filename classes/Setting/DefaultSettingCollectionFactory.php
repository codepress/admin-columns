<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class DefaultSettingCollectionFactory
{

    public static function create(Config $config): SettingCollection
    {
        return new SettingCollection([
            (new NameFactory())->create($config),
            (new LabelFactory())->create($config),
            (new WidthFactory())->create($config),
        ]);
    }

}