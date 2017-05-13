<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\Facades\Emulators;

/**
 * Emulator Communication service provider.
 *
 * This provider enables communication to WoW emulators,
 * through the \Emulators Facade or optionally, by injecting the EmulatorManager.
 */
class EmulatorServiceProvider extends ServiceProvider
{
    /**
     * Services provided by the service provider.
     *
     * @var array
     */
    protected $provides = [];

    /**
     * Bootstrap the application emulator.servers.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/emulator.php' => config_path('emulator.php')
        ], 'wow.emulators');
    }

    /**
     * Register the application emulator.servers.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->config->has('emulator')) {
            $this->app->config->set('emulator', require __DIR__.'/../config/emulator.php');
        }

        $this->registerEmulatorManagerAndAliasFacade();

        $this->bindCommunicationHandlerAliases();

        $this->registerGenericDrivers();

        $this->aliasCommunicationPipes();

        EmulatorCommand::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the Manager and alias the Facade to it.
     *
     * @return void
     */
    protected function registerEmulatorManagerAndAliasFacade()
    {
        $this->app->singleton(EmulatorManager::class, function ($app) {
            return new EmulatorManager($app);
        });

        $this->app->alias(EmulatorManager::class, Emulators::class);

        $this->addProvides([EmulatorManager::class, Emulators::class]);
    }

    /**
     * Alias the communication handlers into the their segment,
     * of the Emulator communication namespace.
     *
     * @return void
     */
    protected function bindCommunicationHandlerAliases()
    {
        collect($this->app->config->get('emulator.communication.aliases'))
            ->each(function ($abstract, $alias) {
                $this->app->alias(
                    $abstract,
                    $prefixedAlias = "Emulator.Communication.Handlers.{$alias}"
                );

                $this->addProvides($prefixedAlias);
            });
    }

    /**
     * Register the "generic" communication drivers with the Manager.
     *
     * @return void
     */
    protected function registerGenericDrivers()
    {
        $manager = $this->app->make(EmulatorManager::class);
        collect($this->app->config->get('emulator.drivers'))->keys()
            ->reject(function ($driver) use ($manager) {
                return $manager->hasDriver($driver);
            })
            ->each(function ($driver) use ($manager) {
                return $manager->extend($driver, $manager->genericDriverCallback($driver));
            });
    }

    /**
     * Alias the communication pipes into their segment of the emulator communication namespace.
     *
     * @return void
     */
    protected function aliasCommunicationPipes()
    {
        foreach ($this->app->config->get('emulator.communication.pipes') as $pipe) {
            $this->app->alias(
                $pipe,
                $alias = 'Emulators.Communication.Pipes.'.class_basename($pipe)
            );
            
            $this->addProvides($alias);
        }
    }

    /**
     * Add a service provided by this Service Provider.
     *
     * @param dynamic $services
     */
    public function addProvides(...$services)
    {
        foreach (Arr::collapse($services) as $service) {
            if (is_string($service)) {
                $this->provides[] = $service;
            } else {
                $this->provides[] = get_class($service);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return $this->provides;
    }
}
