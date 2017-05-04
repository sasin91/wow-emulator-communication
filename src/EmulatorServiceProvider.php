<?php

namespace Sasin91\WoWEmulatorCommunication;

use App\TrinityCore\Communication\CommunicatorContract;
use App\TrinityCore\Communication\RemoteAccess;
use App\TrinityCore\Communication\Soap;
use Artisaninweb\SoapWrapper\Service;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Sasin91\WoWEmulatorCommunication\Command\Validators\CommandPresenceValidator;
use Sasin91\WoWEmulatorCommunication\Communication\SoapCommunicator;
use Sasin91\WoWEmulatorCommunication\Communication\SocketCommunicator;
use Sasin91\WoWEmulatorCommunication\Communication\TrinityCore\TrinityCoreRemoteAccessCommunicator;
use Sasin91\WoWEmulatorCommunication\Communication\TrinityCore\TrinityCoreSoapCommunicator;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;
use Sasin91\WoWEmulatorCommunication\Facades\Emulators;
use Socket\Raw\Factory;

class EmulatorServiceProvider extends ServiceProvider
{
    protected $provides = [];

    /**
     * Bootstrap the application emulator.servers.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/emulator.php' => config_path()
        ], 'wow.emulators');
    }

    /**
     * Register the application emulator.servers.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->config->has('emulator') ) {
            $this->app->config->set('emulator', require __DIR__.'/../config/emulator.php');
        }

        $this->registerEmulatorManagerAndAliasFacade();

        $this->bindCommunicatorAliases();

        $this->registerGenericDrivers();    

        $this->aliasCommunicationPipes();
    }

    protected function registerEmulatorManagerAndAliasFacade()
    {
        $this->app->singleton(EmulatorManager::class, function ($app) {
            return new EmulatorManager($app);
        });

        $this->app->alias(EmulatorManager::class, Emulators::class);

        $this->addProvides([EmulatorManager::class, Emulators::class]);
    }

    protected function bindCommunicatorAliases()
    {
        collect($this->app->config->get('emulator.communication.aliases'))
            ->each(function ($abstract, $alias) {
                $this->app->alias(
                    $abstract, 
                    $prefixedAlias = "Emulator.Communication.Communicators.{$alias}"
                );

                $this->addProvides($prefixedAlias);
            }); 
    }

    protected function registerGenericDrivers()
    {
        $manager = $this->app->make(EmulatorManager::class);
        collect($this->app->config->get('emulator.drivers'))->keys()
            ->reject(function ($driver) use($manager) {
                return $manager->hasDriver($driver);
            })
            ->each(function ($driver) use($manager) {
                return $manager->extend($driver, $manager->genericDriverCallback($driver));
            });
    }

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