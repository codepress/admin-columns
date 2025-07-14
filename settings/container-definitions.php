<?php

use AC\Admin;
use AC\Admin\PageRequestHandlers;
use AC\AdminColumns;
use AC\Asset\Script\GlobalTranslationFactory;
use AC\Asset\Script\Localize\Translation;
use AC\ListScreenRepository;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\ListScreenRepository\Types;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\RequestHandler\Ajax\RestoreSettingsRequest;
use AC\Service\PluginUpdate;
use AC\Setting\ContextFactory;
use AC\Storage\EncoderFactory;
use AC\Storage\Table;
use AC\TableIdsFactory;
use AC\TableScreenFactory;
use AC\Type\Url\Site;
use AC\Vendor\DI\Container;
use Psr\Container\ContainerInterface;

use function AC\Vendor\DI\autowire;
use function AC\Vendor\DI\get;

return [
    'translations.global'                   => static function (AdminColumns $plugin): Translation {
        return new Translation(require $plugin->get_dir() . 'settings/translations/global.php');
    },
    ContainerInterface::class               => autowire(Container::class),
    Storage::class                          => static function (Database $database): Storage {
        $storage = new Storage();
        $storage->set_repositories([
            Types::DATABASE => new ListScreenRepository\Storage\ListScreenRepository($database, true),
        ]);

        return $storage;
    },
    RestoreSettingsRequest::class           => static function (Storage $storage): RestoreSettingsRequest {
        return new RestoreSettingsRequest($storage->get_repository(Types::DATABASE));
    },
    AdminColumns::class                     => static function (): AdminColumns {
        return new AdminColumns(AC_FILE, new Version(AC_VERSION));
    },
    TableScreenFactory::class               => autowire(TableScreenFactory\Aggregate::class),
    SetupFactory\AdminColumns::class        => static function (
        AdminColumns $plugin,
        Table\AdminColumns $table
    ): SetupFactory\AdminColumns {
        return new SetupFactory\AdminColumns('ac_version', $plugin->get_version(), $table);
    },
    GlobalTranslationFactory::class         => autowire()
        ->constructorParameter(1, get('translations.global')),
    TableIdsFactory::class                  => autowire(TableIdsFactory\Aggregate::class),
    Admin\Colors\Shipped\ColorParser::class => autowire()
        ->constructorParameter(0, ABSPATH . 'wp-admin/css/common.css'),
    Admin\Colors\ColorReader::class         => autowire(Admin\Colors\ColorRepository::class),
    Admin\Admin::class                      => autowire()
        ->constructorParameter(0, get(PageRequestHandlers::class)),
    Admin\MenuFactoryInterface::class       => autowire(Admin\MenuFactory::class)
        ->constructorParameter(0, admin_url('options-general.php')),
    Admin\PageFactory\Settings::class       => autowire()
        ->constructorParameter(2, defined('ACP_FILE')),
    Admin\PageFactory\Help::class           => autowire()
        ->constructorParameter(0, get(AdminColumns::class)),
    EncoderFactory::class                   => static function (AdminColumns $plugin) {
        return new EncoderFactory\BaseEncoderFactory($plugin->get_version());
    },
    ContextFactory\Aggregate::class         => autowire()
        ->constructorParameter(0, get(ContextFactory\Column::class)),
    ContextFactory::class                   => get(ContextFactory\Aggregate::class),
    PluginUpdate::class                     => autowire()
        ->constructorParameter(0, get(AdminColumns::class))
        ->constructorParameter(1, new Site('upgrade-to-ac-version-%s')),
];