<?php

namespace Sasin91\WoWEmulatorCommunication\Communication\Pipes;

use App\Models\World\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
* Emulator EmulatorCommand Permission Validator
*/
class VerifyCommandPresence
{
	/**
	 * Pipe configuration
	 * 
	 * @var array
	 */
	protected $config = [];

	public function __construct($config)
	{
		$this->config = Arr::wrap($config);
	}

	public function handle($command, \Closure $next)
	{
		DB::connection($this->config['database']['connection'])
		  ->table($this->config['database']['table'])
		  ->where('name', $command)
		  ->firstOrFail();

		return $next($command);
	}
}