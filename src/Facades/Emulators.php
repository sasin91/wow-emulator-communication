<?php

namespace Sasin91\WoWEmulatorCommunication\Facades;

use Sasin91\WoWEmulatorCommunication\EmulatorManager;
use Illuminate\Support\Facades\Facade;

class Emulators extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmulatorManager::class;
    }
}