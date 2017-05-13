<?php

namespace Sasin91\WoWEmulatorCommunication\Testing;

use Illuminate\Support\Str;
use Sasin91\WoWEmulatorCommunication\EmulatorDriverCollection;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;
use Sasin91\WoWEmulatorCommunication\Testing\Concerns\ManagesDispatchedCommands;
use Sasin91\WoWEmulatorCommunication\Testing\FakeCommunicationDriver;

class FakeEmulatorManager extends EmulatorManager
{
    use ManagesDispatchedCommands;

	/**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        if (Str::startsWith($driver, 'multiple')) {
            return new EmulatorDriverCollection([new FakeCommunicationDriver($this)]);
        }

        return new FakeCommunicationDriver($this);
    }

    /**
     * Checks whether the Manager has a given driver.
     *
     * @param  string  $driver
     * @return boolean
     */
    public function hasDriver($driver)
    {
        return $driver !== 'fire' && $driver !== 'command';
    }
}