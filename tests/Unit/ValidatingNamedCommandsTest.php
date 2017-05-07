<?php

namespace Sasin91\WoWEmulatorCommunication\Tests\Unit;

use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use Sasin91\WoWEmulatorCommunication\Commands\CreateAccountCommand;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\Tests\Concerns\CommandTestTrait;

class ValidatingNamedCommandsTest extends TestCase
{
	use CommandTestTrait;

	/**
	 * @covers Sasin91\WoWEmulatorCommunication\NamedEmulatorCommandContract@fire
	 * @covers Sasin91\WoWEmulatorCommunication\Commands\Concerns\Validatable@validate
	 * 
	 * @test
	 */
	public function validation_fails_if_parameters_does_not_match_rules()
	{
		try {
	    	\Emulators::fire(new CreateAccountCommand('john', false));
	    } catch (ValidationException $e) {
	    	$this->assertArrayHasKey('password', $e->validator->failed());
	    }
	}
}