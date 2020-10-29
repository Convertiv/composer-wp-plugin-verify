<?php

/**
 * @file
 * Contains Convertiv\PreservePaths\Plugin.
 */

namespace Convertiv\Composer\WPVerify;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Composer\Script\Event;
use Composer\Package\BasePackage;

/**
 * Wrapper for making Plugin debuggable.
 */
class PluginWrapper
{

    /**
     * @var \Composer\IO\IOInterface
     */
    protected $io;

    /**
     * @var \Composer\Composer
     */
    protected $composer;

    /**
     * @var \Composer\Util\Filesystem
     */
    protected $filesystem;


    /**
     * {@inheritdoc}
     */
    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
        $this->composer = $composer;
        $this->filesystem = new Filesystem();
    }

    /**
     * Post install event for verifying that the packages exist
     *
     * @param \Composer\Script\Event $event
     */
    public function verifyPackages(Event $event)
    {
        $repository = $this->composer->getRepositoryManager()->getLocalRepository();
        foreach ($repository->getPackages() as $package) {
            if ($package instanceof BasePackage) {
                $this->verifyPackage($package);
            }
        }
    }
    /**
     * Verify a package folder exists and is not empty
     *
     * @param BasePackage  $package  The package to clean
     * @return bool True if cleaned
     */
    protected function verifyPackage(BasePackage $package)
    {
        // Only verify wordpress-plugin types

        if ($package->getType() !== 'wordpress-plugin') {
            return false;
        }

        $installer =  new \Composer\Installers\WordPressInstaller($package, $this->composer, $this->io);
        $dir = $installer->getInstallPath($package);

        // Throw error if nothing exists
        if (!file_exists($dir)) {
            throw new \RuntimeException(
                $dir . ' does not exist.'
            );
        }

        // Throw error if dir doesn't exist
        if (!is_dir($dir)) {
            throw new \RuntimeException(
                $dir . ' does not exist.'
            );
        }

        // Throw error if dir is empty
        if ($this->dirEmpty($dir)) {
            throw new \RuntimeException(
                $dir . ' is empty.'
            );
        }

        return true;
    }

    /**
     * Check if a directory is empty
     *
     * @param string $dir
     * @return void
     */
    public function dirEmpty($dir)
    {
        if (!is_dir($dir)) return false;
        foreach (scandir($dir) as $file) {
            if (!in_array($file, array('.', '..', '.svn', '.git'))) return false;
        }
        return true;
    }
}
