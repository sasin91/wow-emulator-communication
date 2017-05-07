<?php

namespace Sasin91\WoWEmulatorCommunication\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Sasin91\WoWEmulatorCommunication\Commands\CreateAccountCommand;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\Tests\Concerns\CommandTestTrait;

class DispatchingCommandsTest extends TestCase
{
	use CommandTestTrait;

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\EmulatorManager::driver()
	 * @covers Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationDriver::command()
	 * @covers Sasin91\WoWEmulatorCommunication\Communication\CommunicationPipeline
	 * @covers Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler::handle()
	 * 
	 * @test
	 */
	public function it_can_dispatch_a_command_to_a_single_emulator()
	{
		$this->assertEquals(
			'Hello world',
			\Emulators::command(new EmulatorCommand('Hello', ['world']))
		);
	}

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\EmulatorManager::driver()
	 * @covers Sasin91\WoWEmulatorCommunication\EmulatorDriverCollection
	 * @covers Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationDriver::command()
	 * @covers Sasin91\WoWEmulatorCommunication\Communication\CommunicationPipeline
	 * @covers Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler::handle()
	 * 
	 * @test
	 */
	public function it_can_dispatch_a_command_across_multiple_emulators()
	{
		$results = \Emulators::dispatchTo('multiple-testing', new EmulatorCommand('Hello world'));

		$results->each(function ($response) {
			$this->assertEquals('Hello world', $response);
		});
	}

	/** 
	 * @covers Sasin91\WoWEmulatorCommunication\EmulatorCommand::formatParameters()
	 * 
	 * @test
	 */
	public function it_implodes_an_array_of_parameters_into_a_argument_string()
	{
		$command = (new EmulatorCommand('Hello world', ['parameter1', 'parameter2']))->useDelimiter(', ');
		$this->assertEquals(
			'Hello world parameter1, parameter2',
			\Emulators::fire($command)
		);
	}

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\Facades\Emulators::__callStatic($method, $args)
	 *
	 * @test
	 */
	public function it_proxies_dynamic_commands()
	{
		$this->assertEquals(
			"hello world how is it spinning?",
			\Emulators::Testing('hello world', 'how is it spinning?')
		);
	}

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\NamedEmulatorCommandContract@fire
	 * @covers Sasin91\WoWEmulatorCommunication\Commands\Concerns\Validatable@validate
	 * 
	 * @test
	 */
	public function it_can_dispatch_a_named_command_object()
	{
	    $this->assertEquals(
	    	"account create john secret",
	    	\Emulators::fire(new CreateAccountCommand('john', 'secret'))
	    );
	}
}
