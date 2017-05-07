<?php

namespace Sasin91\WoWEmulatorCommunication\Commands;

use Sasin91\WoWEmulatorCommunication\Commands\Concerns\NamedCommand;
use Sasin91\WoWEmulatorCommunication\Commands\Concerns\Validatable;
use Sasin91\WoWEmulatorCommunication\NamedEmulatorCommandContract;

/**
* Register an account on the remote emulator through it's exposed API.
*/
class CreateAccountCommand implements NamedEmulatorCommandContract
{
    use NamedCommand, Validatable;

    /**
     * Construct a named command for registering an account.
     *
     * @param string $name
     * @param string $password
     */
    public function __construct($name, $password)
    {
        $this->parameters = ['name' => $name, 'password' => $password];

        $this->rules([
            'name'        =>    ['required', 'string'],
            'password'    =>    ['required', 'string']
        ]);
    }

    /**
     * Get the command string.
     *
     * @return string
     */
    public function command()
    {
        return 'account create';
    }

    /**
     * Get the name of the underlying driver
     * or null for default.
     *
     * @return string|null
     */
    public function driver()
    {
        return null;
    }
}
