<?php

declare(strict_types=1);

namespace AC\Build\Task;

use AC\Build\Exception\DirectoryException;
use AC\Build\Exception\FileException;
use AC\Build\Exception\ProcessException;
use AC\Build\PrefixerFactory;
use AC\Build\Task;
use SplFileInfo;

final class Prefix implements Task
{

    private PrefixerFactory $prefixerFactory;

    public function __construct(PrefixerFactory $prefixerFactory)
    {
        $this->prefixerFactory = $prefixerFactory;
    }

    /**
     * @throws DirectoryException
     * @throws FileException
     * @throws ProcessException
     */
    public function run(): void
    {
        global $argv;

        // Release builds pass `--no-dev` so dev-only tooling (e.g. php-cs-fixer)
        // is neither scoped nor shipped inside the plugin.
        $isDevelopment = ! in_array('--no-dev', $argv ?? [], true);

        $prefixer = $this->prefixerFactory->create(new SplFileInfo(getcwd()), $isDevelopment);
        $prefixer->prefix();
    }

}