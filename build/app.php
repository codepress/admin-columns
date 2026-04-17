<?php

declare(strict_types=1);

use AC\Build\PrefixerFactory;
use AC\Build\Router;
use AC\Build\Task;
use AC\Build\Utility\Shell;
use DI\ContainerBuilder;

require __DIR__ . '/vendor/autoload.php';

$router = new Router([
    'prefix' => Task\Prefix::class,
]);

$definitions = [
    'prefixer.version'     => '0.17.6',
    'prefixer.url'         => DI\string(
        'https://github.com/humbug/php-scoper/releases/download/{prefixer.version}/php-scoper.phar'
    ),
    PrefixerFactory::class => DI\autowire()
        ->constructorParameter(0, DI\get('prefixer.url')),
];

try {
    $container = (new ContainerBuilder)
        ->addDefinitions($definitions)
        ->build();

    $container->get($router->getTask())->run();
} catch (Exception $e) {
    Shell::error(get_class($e) . ': ' . $e->getMessage());
}
