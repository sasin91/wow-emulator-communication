<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait ParsesParameters
{
    /**
     * The command parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * The parameter delimiter
     *
     * @var string
     */
    protected $delimiter = ' ';

    /**
     * Use given delimiter
     *
     * @param  string $delimiter
     * @return $this
     */
    public function useDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Get the current parameters,
     * optionally override the current parameters.
     *
     * @return array
     */
    public function parameters()
    {
        if (func_num_args() > 0) {
            $this->parameters = Arr::wrap(...func_get_args());
        }

        return $this->parameters;
    }

    /**
     * Add an array of parameters to the command query.
     *
     * @param array $parameters
     */
    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        
        return $this;
    }

    /**
     * Format the parameters.
     *
     * @return string
     */
    protected function formatParameters()
    {
        return Str::replaceLast(
            $this->delimiter,
            PHP_EOL,
            (new Collection($this->parameters))->implode($this->delimiter)
        );
    }
}
