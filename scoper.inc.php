<?php

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix'                  => 'AC\Vendor',
    'finders'                 => [
        Finder::create()
              ->files()
              ->ignoreVCS(true)
              ->in('vendor'),
        Finder::create()->append([
            'composer.json',
        ]),
    ],
    'exclude-files'           => [
        'vendor/php-di/php-di/src/Compiler/Template.php',
    ],
    'patchers'                => [],
    'exclude-namespaces'      => [],
    'exclude-classes'         => [],
    'exclude-functions'       => [],
    'exclude-constants'       => [],
    'expose-global-constants' => true,
    'expose-global-classes'   => true,
    'expose-global-functions' => true,
    'expose-namespaces'       => [],
    'expose-classes'          => [],
    'expose-functions'        => [],
    'expose-constants'        => [],
];