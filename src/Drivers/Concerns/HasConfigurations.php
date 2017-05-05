<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers\Concerns;


use Illuminate\Support\Arr;

trait HasConfigurations
{
    /**
	 * Array of Configurations
	 *
	 * @var array
	 */
    protected $config;

    /**
     * Return the current Configuration.
     *
     * @param null|string   $key
     * @param mixed         $default
     * @return array
     */
    public function config($key = null, $default = null)
    {
        return is_null($key)
            ? $this->config
            : Arr::get($this->config, $key, $default);
    }
}