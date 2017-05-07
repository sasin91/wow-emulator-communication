<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait DispatchesDynamicCommands
{
    public $dynamicCommandDelimiter = ' ';

    /**
     * Dynamically dispatch commands,
     * by parsing a camelCased or snake_cased method name as a command.
     *
     * eg. Emulator::accountOnlineList() or Emulator::account_online_list()
     *
     * @param $method
     * @param array $parameters
     * @return Collection|string
     */
    public function __call($method, array $parameters = [])
    {
        // if a CamelCased $method was given,
        // snake case it.
        // otherwise assume a snake_cased $method was given.
        if (preg_match("(['A-Z']['a-z']+)", $method)) {
            $method = Str::snake($method);
        }

        // Next the the snake_cased $method goes boom.
        $segments = explode('_', $method);

        // Then stitch the $segments back together,
        // with the dynamicCommandDelimiter instead of underscore,
        // to create our $command.
        $command = implode($this->dynamicCommandDelimiter, $segments);

        // Finally fire that phat command.
        return $this->command($command, $parameters);
    }
}
