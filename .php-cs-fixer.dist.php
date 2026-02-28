<?php

$root = __DIR__;

// Recursive targets
$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in($root . '/classes')
    ->exclude(['vendor', 'node_modules']);

// Root-level PHP files only (non-recursive)
$rootFinder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in($root)
    ->depth('== 0')
    ->notName('scoper.inc.php');

$finder->append($rootFinder);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(require $root . '/settings/php-cs-fixer/rules.php')
    ->setFinder($finder);
