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
		//
	}

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
	{
		parent::setUp();

		\Emulators::fake();
	}

}