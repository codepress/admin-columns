<?php

$root = __DIR__;

// Recursive targets
$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in($root . '/classes')
    ->append([
        $root . '/api.php',
        $root . '/codepress-admin-columns.php',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(require $root . '/settings/php-cs-fixer/rules.php')
    ->setFinder($finder);
