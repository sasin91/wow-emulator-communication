<?php

namespace Sasin91\WoWEmulatorCommunication\Communication;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Arr;

/**
* Communication Pipeline
*
* allows for running middleware before the command is dispatched to the remote API.
*/
class CommunicationPipeline implements Pipeline
{

    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The object being passed through the pipeline.
     *
     * @var mixed
     */
    protected $command;

    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected $pipes = [];

    /**
     * The method to call on each pipe.
     *
     * @var string
     */
    protected $method = 'handle';

    /**
     * Construct the CommunicationPipeline.
     *
     * @param null|Container $container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    /**
     * Set the command object being sent on the pipeline.
     *
     * @param  mixed  $command
     * @return $this
     */
    public function send($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Set the pipes of the pipeline.
     *
     * @param  dynamic|array  $pipes
     * @return $this
     */
    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();
        
        return $this;
    }

    /**
     * Set the method to call on the pipes.
     *
     * @param  string  $method
     * @return $this
     */
    public function via($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Run the pipeline with a final destination callback.
     *
     * @param  \Closure  $destination
     * @return mixed
     */
    public function then(\Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            function ($passable) use ($destination) {
                return $destination($passable);
            }
        );

        return $pipeline($this->command);
    }

     /**
     * Get a Closure that represents a slice of the application onion.
     *
     * @return \Closure
     */
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($command) use ($stack, $pipe) {
                if (is_array($pipe)) {
                    if (is_string(head($pipe))) {
                        $pipe = head($pipe);
                    } else {
                        $data = $pipe;
                        $pipe = head(array_keys($data));
                        $config = head(array_values($data));
                    }
                }

                // if the pipe is actually a Closure,
                // we'll pop it and return the results.
                if ($pipe instanceof \Closure) {
                    return $pipe($command, $stack);
                }

                $parameters = [$command, $stack];
                if (is_string($pipe)) {
                    // when the pipe is a string, we'll assume it's class reference,
                    // optionally with parameters padded on.
                    list($class, $arguments) = $this->parsePipeString($pipe);
                    $parameters = array_merge($parameters, $arguments);

                    $config = $config ?? Arr::get($this->pipes, $class, []);
                    if ($this->container()->bound($class)) {
                        $pipe = $this->container()->call($class, $config);
                    } elseif (class_exists($class)) {
                        $pipe = $this->container()->make($class, $config);
                    } else {
                        $class = $this->container()->getAlias("Emulators.Communication.Pipes.{$class}");

                        $pipe = new $class($config);
                    }
                }

                // at this point, we should have an object $pipe and a set of $parameters ready,
                // so lets fire this phat pipe and return results.
                return $pipe->{$this->method}(...$parameters);
            };
        };
    }

    /**
     * Parse full pipe string to get name and parameters.
     *
     * @param  string $pipe
     * @return array
     */
    protected function parsePipeString($pipe)
    {
        list($class, $parameters) = array_pad(explode(':', $pipe, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$class, $parameters];
    }

    /**
     * If any, get the current container instance
     * or default the Laravel Container.
     *
     * @return Container
     */
    protected function container()
    {
        return $this->container ?? $this->container = app();
    }
}
