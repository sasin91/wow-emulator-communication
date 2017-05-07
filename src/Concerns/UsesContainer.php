<?php

namespace Sasin91\WoWEmulatorCommunication\Concerns;

use Illuminate\Contracts\Container\Container;

/**
 * Enables the usage of a DI Container.
 *
 * Trait UsesContainer
 * @package Sasin91\WoWEmulatorCommunication\Concerns
 */
trait UsesContainer
{
    /**
     * The Container instance
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
	 * Use the given Container.
	 *
	 * @param  Container $container
	 * @return $this
	 */
    public function useContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
	 * Get the Container instance.
	 * defaults to Laravel container through app() helper.
	 *
	 * @return \Illuminate\Contracts\Container\Container
	 */
    protected function container()
    {
        return $this->container ? $this->container : $this->container = app();
    }
}