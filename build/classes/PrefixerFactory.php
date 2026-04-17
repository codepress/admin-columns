<?php

declare(strict_types=1);

namespace AC\Build;

use AC\Build\Exception\DirectoryException;
use AC\Build\Exception\FileException;
use SplFileInfo;

final class PrefixerFactory
{

    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @throws DirectoryException
     * @throws FileException
     */
    public function create(SplFileInfo $composerRoot, bool $isDevelopment = true): Prefixer
    {
        return new Prefixer($composerRoot, $this->url, $isDevelopment);
    }

}