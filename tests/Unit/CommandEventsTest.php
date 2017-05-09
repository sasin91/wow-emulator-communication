<?php

namespace Sasin91\WoWEmulatorCommunication\Tests\Unit;

use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandCreated;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandCreating;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFired;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFiring;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\Tests\Concerns\CommandTestTrait;

class CommandEventsTest extends TestCase
{
	use CommandTestTrait;

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\Commands\Concerns\HasEvents@fireCommandEvent
	 *
	 * @test
	 */
	public function it_fires_the_expected_events()
	{
		// First, lets replace the Event dispatcher to speed up the test...
		\Event::fake();
		EmulatorCommand::setEventDispatcher(\Event::getFacadeRoot());

		// Create a new command, assert the creating & created events got dispatched
		// but not the firing & fired events.
		$command = new EmulatorCommand('hello world');
		\Event::assertDispatched(CommandCreating::class, function ($event) {
			return 'hello world' === (string)$event->command;
		});
		\Event::assertDispatched(CommandCreated::class);
		\Event::assertNotDispatched(CommandFiring::class);
		\Event::assertNotDispatched(CommandFired::class);

		// Fire thecommand off, assert the firing and fired events are now dispatched.
		$command->fire();
		\Event::assertDispatched(CommandFiring::class, function ($event) {
			return 'hello world' === (string)$event->command;
		});
		\Event::assertDispatched(CommandFired::class);
	}
}