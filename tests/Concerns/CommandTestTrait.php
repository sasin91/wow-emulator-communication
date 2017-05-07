<?php

namespace Sasin91\WoWEmulatorCommunication\Tests\Concerns;

use Sasin91\WoWEmulatorCommunication\EmulatorManager;
use Sasin91\WoWEmulatorCommunication\Testing\AssertCommandPassed;
use Sasin91\WoWEmulatorCommunication\Testing\FakeSoapClient;

trait CommandTestTrait 
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

	    // Enable driver command proxying
	    $config->set('emulator.proxy-driver-commands', true);
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

		\Emulators::swap($this->manager);
	}

}