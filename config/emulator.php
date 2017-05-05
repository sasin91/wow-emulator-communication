<?php 
use Sasin91\WoWEmulatorCommunication\Communication\Pipes\VerifyCommandPresence;
use Sasin91\WoWEmulatorCommunication\Communication\SoapHandler;
use Sasin91\WoWEmulatorCommunication\Communication\SocketHandler;

return [
	
	/*
    |--------------------------------------------------------------------------
    | Emulator command proxying
    |--------------------------------------------------------------------------
    |
    | Here you may enable command proxying feature,
    | this allows for an expressive and fluent access to emulator drivers,
    |
    | Using the TrinityCore driver as an example, where you would write,
    | \Emulators::driver('TrinityCore')->$method(...$parameters),
    | you can now write \Emulators\TrinityCore::$method(...$parameters).
    |
    */
	'proxy-driver-commands' =>	false,	

	/*
    |--------------------------------------------------------------------------
    | Default Emulator driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default World of Warcraft Emulator that should be used.
    | 
    | The default should be present in the array of drivers below.
    |
    */
	'default'	=>	env('EMULATOR_DEFAULT', 'TrinityCore'),

	/*
    |--------------------------------------------------------------------------
    | Emulator drivers
    |--------------------------------------------------------------------------
    | 
    | Here you may specify and configure as many Emulator drivers as you wish,
    | you may even reference multiple emulator drivers on the a driver prefixed with 
    | "multiple" driver!
    | 
    | Supported Drivers: "TrinityCore", "CMangos", "Multiple"
    |
    | It is possible to configure the drivers,
    | specifying what communication handler should be used, by the 'handler' key.
    | 
    | You may also configure which pipes the driver should pass the command through,
    | before passing it to the communication handler.
    |
    | Additionally it is also possible to register your own pipes.
    */
	'drivers'	=>	[
		'multiple'	=>	['TrinityCore', 'CMangos'],

		'TrinityCore'	=>	[
			'handler'	=>	'soap',

		    'credentials'   =>  [
	           'login'     =>  env('TC_USER', 'admin'),
	           'password'  =>  env('TC_PASS', 'admin')
	        ],

	        'pipes'	=>	[
	        	'VerifyCommandPresence'	=>	[
	        		'database'	=>	[
	        			'connection'	=>	env('TC_WORLD_CONNECTION', 'world'),
	        			'table'			=>	env('TC_WORLD_COMMANDS_TABLE', 'commands')
	        		]
	        	]
	        ]
		],

		'CMangos'	=>	[
			'handler'	=>	'soap',

			'credentials'   =>  [
	           'login'     =>  env('CMANGOS_USER', 'admin'),
	           'password'  =>  env('CMANGOS_PASS', 'admin')
	        ],

	        'pipes'	=>	[
	        	'VerifyCommandPresence'	=>	[
	        		'database'	=>	[
	        			'connection'	=>	env('CMANGOS_WORLD_CONNECTION', 'world'),
	        			'table'			=>	env('CMANGOS_WORLD_COMMANDS_TABLE', 'commands')
	        		]
	        	]
	        ]
		]
	],

	'communication'	=>	[
		/*
		|--------------------------------------------------------------------------
	    | Communication pipes
	    |--------------------------------------------------------------------------
		|	
		| The emulator communication pipes, think of these as middleware,
		| that runs before the command is sent to the remote API.
		|
		| by default an alias with the class base name will be created for each pipe,
		| making it possible to reference a pipe by by a string, without a namespace.
		*/
		'pipes'	=>	[
			VerifyCommandPresence::class
		],

		/*
		|--------------------------------------------------------------------------
	    | Handler aliases
	    |--------------------------------------------------------------------------
		|	
	    | The communication aliases,
	    | these aliases makes it possible to reference the communicators, 
	    | by a string without namespace.
	    |
	    | These aliases will get prefixed, so they don't conflict with 
	    | existing system aliases.
	    | eg. Emulator.Communication.Handlers.soap
	    |
	    | Note, when setting a driver handler, 
	    | you should not write the full prefixed alias,
	    | but rather just the alias itself.
		*/		
		'aliases'	=>	[
			'soap'		=>	SoapHandler::class,
			'socket'	=>	SocketHandler::class
		]
	],

	/*
	|--------------------------------------------------------------------------
    | Servers
    |--------------------------------------------------------------------------
	|	
    | Here you may define the emulator servers, and their API protocols.
	*/
	'servers'	=>	[
		'TrinityCore'   =>  [
	        'soap'    =>  [
	            'location'  =>  env('TC_SOAP_LOCATION', 'http://127.0.0.1:7878'),
	            'uri'       =>  env('TC_SOAP_URI', 'urn:TC'),
	            'style'     =>  env('TC_SOAP_STYLE', SOAP_RPC),
	        ],

	        'socket'	=>  [
	              'location'  =>  env('TC_RA_LOCATION', 'tcp://127.0.0.1:3443')
	        ]
	    ],

        'CMangos'   =>  [
            'soap'    =>  [
                'location'  =>  env('CMANGOS_SOAP_LOCATION', 'http://127.0.0.1:7878'),
                'uri'       =>  env('CMANGOS_SOAP_URI', 'urn:MaNGOS'),
                'style'     =>  env('CMANGOS_SOAP_STYLE', SOAP_RPC),
            ],

            'socket'	=>  [
                'location'  =>  env('CMANGOS_RA_LOCATION', 'tcp://127.0.0.1:3443')
            ]
        ]
	]
];