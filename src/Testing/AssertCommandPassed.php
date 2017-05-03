<?php

namespace Sasin91\WoWEmulatorCommunication\Testing;

class AssertCommandPassed
{
	public function handle($command = null, $next)
	{
		if ($command !== NULL) {
			return $this->success($next, $command);
		}

		return $this->fail();
	}

	public function success($next, $command)
	{
		return $next($command);
	}

	public function fail()
	{
		// Nothing to do
	}
}