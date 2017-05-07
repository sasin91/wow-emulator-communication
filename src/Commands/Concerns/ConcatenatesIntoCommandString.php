<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

trait ConcatenatesIntoCommandString
{
    use ParsesParameters;

    /**
     * Get the command represented as a string.
     *
     * @return string
     */
    public function __toString()
    {
        $command = $this->command();
        $parameters = $this->formatParameters();
        return rtrim("{$command} {$parameters}");
    }
}
