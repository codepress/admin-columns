<?php

declare(strict_types=1);

namespace AC\Build;

use AC\Build\Exception\DirectoryException;
use AC\Build\Exception\FileException;
use AC\Build\Exception\ProcessException;
use AC\Build\Utility\Shell;
use Exception;
use SplFileInfo;
use Symfony\Component\Process\Process;

final class Prefixer
{

    private const AUTOLOADER_PREFIX = 'ac-';

    protected SplFileInfo $composerRoot;

    private string $url;

    private bool $isDevelopment;

    /**
     * @throws DirectoryException
     * @throws FileException
     */
    public function __construct(SplFileInfo $composerRoot, string $url, bool $isDevelopment)
    {
        $this->composerRoot = $composerRoot;
        $this->url = $url;
        $this->isDevelopment = $isDevelopment;

        $this->validate();
    }

    /**
     * @throws DirectoryException
     * @throws FileException
     */
    protected function validate(): void
    {
        if ( ! $this->composerRoot->isDir()) {
            throw DirectoryException::DirectoryNotReadable($this->composerRoot->getPath());
        }

        $composerFile = $this->composerRoot->getRealPath() . '/composer.json';

        if ( ! is_readable($composerFile)) {
            throw FileException::FileNotReadable($composerFile);
        }

        $scoperConfigFile = $this->composerRoot->getRealPath() . '/scoper.inc.php';

        if ( ! is_readable($scoperConfigFile)) {
            throw FileException::FileNotReadable($scoperConfigFile);
        }
    }

    /**
     * @throws DirectoryException
     * @throws FileException
     * @throws ProcessException
     */
    public function prefix(): void
    {
        $buildDirectory = './_prefixer';

        $this->run('rm -rf vendor', false);
        $this->run(sprintf('composer install --no-scripts %s', ! $this->isDevelopment ? '--no-dev' : ''));
        $this->applyPatches();
        $this->run(sprintf('curl -O -L "%s"', $this->url));
        $this->run(sprintf('php -d memory_limit=-1 php-scoper.phar add-prefix --force --output-dir %s', $buildDirectory));
        $this->run('rm -rf php-scoper.phar vendor', false);
        $this->run(sprintf('mv %s/vendor ./', $buildDirectory), false);
        $this->run(sprintf('rm -rf %s', $buildDirectory), false);
        $this->run(sprintf('composer dump-autoload --no-scripts %s', ! $this->isDevelopment ? '--classmap-authoritative' : ''));

        $this->patchComposerAutoloader();
    }

    private function applyPatches(): void
    {
        $patchDir = $this->composerRoot->getRealPath() . '/patches';

        if ( ! is_dir($patchDir)) {
            return;
        }

        $patches = glob($patchDir . '/*.patch');

        foreach ($patches as $patch) {
            Shell::newLine();
            Shell::out('<blue>Applying patch:</blue> ' . basename($patch));
            $this->run(sprintf('patch -p1 < %s', escapeshellarg($patch)));
        }
    }

    /**
     * @throws ProcessException
     */
    private function run(string $command, bool $tty = true): void
    {
        $process = Process::fromShellCommandline($command);

        if ($tty) {
            Shell::newLine();
            Shell::out('<blue>$</blue> ' . $process->getCommandLine());
            Shell::newLine();
        }

        try {
            $process->setWorkingDirectory($this->composerRoot->getRealPath())
                    ->setPty($tty)
                    ->run();

            echo $process->getOutput();
        } catch (Exception $e) {
            throw ProcessException::processUnsuccessful($process->getCommandLine(), $e);
        }

        if ($tty) {
            Shell::newLine();
        }
    }

    /**
     * @throws DirectoryException
     * @throws FileException
     */
    private function patchComposerAutoloader(): void
    {
        $composerVendorDirectory = $this->composerRoot->getRealPath() . '/vendor/composer';

        if ( ! is_dir($composerVendorDirectory)) {
            throw DirectoryException::DirectoryDoesNotExist($composerVendorDirectory);
        }

        $filesAutoloaderFile = $composerVendorDirectory . '/autoload_files.php';

        // No files autoloader generated, no patching required
        if ( ! is_file($filesAutoloaderFile)) {
            return;
        }

        $map = [];

        foreach (array_keys(require $filesAutoloaderFile) as $identifier) {
            $map[$identifier] = self::AUTOLOADER_PREFIX . $identifier;
        }

        $autoloaderFileNames = [
            'autoload_files.php',
            'autoload_static.php',
        ];

        foreach ($autoloaderFileNames as $autoloaderFileName) {
            $autoloaderFile = $composerVendorDirectory . '/' . $autoloaderFileName;
            $content = file_get_contents($autoloaderFile);

            if ( ! $content) {
                throw FileException::FileNotReadable($autoloaderFile);
            }

            $result = file_put_contents(
                $autoloaderFile,
                str_replace(
                    array_keys($map),
                    $map,
                    $content
                )
            );

            if ( ! $result) {
                throw FileException::FileNotWritable($autoloaderFile);
            }
        }
    }

}