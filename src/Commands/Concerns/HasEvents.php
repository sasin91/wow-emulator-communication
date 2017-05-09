<?php 

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Illuminate\Contracts\Events\Dispatcher;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandCreated;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandCreating;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFired;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFiring;

/**
 * Command events
 */
trait HasEvents
{
    /**
     * Array of Command events
     *
     * @var array
     */
    protected $events = [
        CommandCreating::class,
        CommandCreated::class,
        CommandFiring::class,
        CommandFired::class
    ];

    /**
     * Event dispatcher
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected static $dispatcher;

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     *
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }

    /**
     * Determine whether the Command has Events enabled.
     *
     * @return boolean
     */
    public static function hasEventDispatcher()
    {
        return isset(static::$dispatcher);
    }

    /**
     * Determine whether the Command has a given Event.
     *
     * @param  string|object  $event
     * @return boolean
     */
    public function hasEvent($event)
    {
        if (! is_string($event)) {
            $event = get_class($event);
        }

        return in_array($event, $this->events);
    }

    /**
     * Register a command event.
     *
     * @param  object $event [Event class]
     * @return $this
     */
    public function registerCommandEvent($event)
    {
        if (! $this->hasEvent($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

     /**
     * Fire the given command event.
     *
     * @param  string   $event
     * @param  bool     $halt
     * @return mixed
     */
    protected function fireCommandEvent($event, $halt = false)
    {
        if (! static::hasEventDispatcher()) {
            return true;
        }

        if (! $this->hasEvent($event)) {
            return null;
        }

        $method = $this->determineEventMethod($halt);
        return static::getEventDispatcher()->$method(new $event($this));
    }

    /**
     * Determine the Event method to be used,
     * based on the halt state.
     *
     * @param  boolean $halt
     * @return string
     */
    protected function determineEventMethod($halt)
    {
        return $halt ? 'until' : 'fire';
    }
}
