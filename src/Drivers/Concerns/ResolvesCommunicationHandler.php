<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers\Concerns;

use Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler;
use Sasin91\WoWEmulatorCommunication\Communication\SoapHandler;

/**
 * Enables the Driver to resolve the communication handler, that should receive the Command after the Pipeline.
 *
 * Trait ResolvesCommunicationHandler
 * @package Sasin91\WoWEmulatorCommunication\Drivers\Concerns
 */
trait ResolvesCommunicationHandler
{
    use UsesContainer;

    /**
     * The Remote API Communication handle.
     *
     * @var \Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler
     */
    protected $communicationHandler;

    /**
     * Initialize the Communication handler
     */
    public function bootResolvesCommunicationHandler()
    {
        $this->communicationHandler = $this->resolveCommunicationHandler();

        $name = "Emulator.Communication.Handler.{$this->name}";
        if ($this->communicationHandler instanceof SoapHandler) {
            $this->configureSoap($name);
        } else {
            $this->configureSocket($name);
        }
    }

    /**
     * Get the CommunicationHandler.
     *
     * @return CommunicationHandler
     */
    public function communicationHandler()
    {
        return $this->communicationHandler;
    }

    /**
     * Resolve the Communication handler through the Container.
     *
     * @return CommunicationHandler
     */
    protected function resolveCommunicationHandler()
    {
        if (class_exists($this->config('handler'))
            || $this->container()->bound($this->config['handler'])
        ) {
            return $this->container()->make($this->config['handler']);
        }

        $handler = 'Emulator.Communication.Handlers.' . $this->config['handler'];
        return $this->container()->make($handler);
    }

    /**
     * Configure the driver for SOAP communication.
     *
     * @param  string $name [Name of the current driver]
     * @return void
     */
    protected function configureSoap($name)
    {
        $options = array_merge(
            $this->config('credentials', []),
            config("emulator.servers.{$this->name}.soap", [])
        );

        $this->communicationHandler()->configure($name, $options);
    }

    /**
     * Configure the driver for Socket communication.
     *
     * @param  string $name [Name of the current driver]
     * @return void
     */
    protected function configureSocket($name)
    {
        $options = array_merge(
            $this->config('credentials', []),
            config("emulator.servers.{$this->name}.ra", [])
        );

        $this->communicationHandler()->configure($name, $options);
    }
}