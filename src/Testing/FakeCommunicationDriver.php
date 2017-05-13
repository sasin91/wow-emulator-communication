<?php

namespace Sasin91\WoWEmulatorCommunication\Testing;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Communication\Concerns\ParsesResponse;
use Sasin91\WoWEmulatorCommunication\Communication\SoapHandler;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\DispatchesDynamicCommands;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\ExecutesCommands;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\HasConfigurations;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\ResolvesCommunicationHandler;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\UsesContainer;
use Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationContract;
use Sasin91\WoWEmulatorCommunication\Testing\FakeEmulatorManager;

class FakeCommunicationDriver implements EmulatorCommunicationContract
{
	use HasConfigurations, DispatchesDynamicCommands, ExecutesCommands, ParsesResponse;

    /**
     * @var FakeEmulatorManager
     */
    private $manager;

    /**
     * FakeCommunicationDriver constructor
     * 
     * @param FakeEmulatorManager $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Execute the EmulatorCommand.
     *
     * @return \Closure
     */
    protected function executeCommand()
    {
        return function ($command) {
            $this->manager->addDispatchedCommand($command);
            return $this->parseResponse((string)$command);
        };
    }
}