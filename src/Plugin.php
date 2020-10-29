<?php

/**
 * @file
 * Contains Convertiv\PreservePaths\Plugin.
 */

namespace Convertiv\Composer\WPVerify;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event;
/**
 * Class Plugin.
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{

    /**
     * @var \Convertiv\PreservePaths\PluginWrapper
     */
    protected $wrapper;

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->wrapper = new PluginWrapper($composer, $io);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::POST_INSTALL_CMD => 'verifyPackage',
        );
    }

    /**
     * After run, use this to verify that packages are installed right
     *
     * @param \Composer\Script\Event $event
     */
    public function verifyPackage(Event $event)
    {

        $this->wrapper->verifyPackages($event);
    }

    /**
     * Remove any hooks from Composer
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Prepare the plugin to be uninstalled
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
