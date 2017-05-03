<?php

namespace Sasin91\WoWEmulatorCommunication\Tests\Feature;
use Orchestra\Testbench\TestCase;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;
use Sasin91\WoWEmulatorCommunication\Testing\AssertCommandPassed;
use Sasin91\WoWEmulatorCommunication\Testing\FakeSoapClient;
use Sasin91\WoWEmulatorCommunication\Testing\FakeSoapServer;

class DispatchingCommandsTest extends TestCase
{
	/**
	 * Emulator Manager
	 * 
	 * @var EmulatorManager
	 */
	protected $manager;

	protected function getPackageProviders($app)
	{
	    return ['Sasin91\WoWEmulatorCommunication\EmulatorServiceProvider'];
	}

	protected function getPackageAliases($app)
	{
	    return [
	        'Emulators' => 'Sasin91\WoWEmulatorCommunication\Facades\Emulators'
	    ];
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$config = $app['config'];

	    // Setup default emulator to a test double
	    $config->set('emulator.default', 'testing');
	    $config->set('emulator.drivers.testing', [
	    	'handler' => FakeSoapClient::class,
	    	'pipes'	  => [AssertCommandPassed::class]
	    ]);

	    // Setup testing-multiple driver
	    $config->set('emulator.drivers.multiple-testing', ['testing']);
	}

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
	{
		parent::setUp();

		$this->manager = new EmulatorManager($this->app);

		foreach (array_keys($this->app->config->get('emulator.drivers')) as $driver) {
			$this->manager->extend(
				$driver, $this->manager->genericDriverCallback($driver)
			);
		}
	}

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
			$this->manager->command(new EmulatorCommand('Hello', ['world']))
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
		$results = $this->manager->driver('multiple-testing')->command(new EmulatorCommand('Hello world'));

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
			$this->manager->command($command)
		);
	}
}
