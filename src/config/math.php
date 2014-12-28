<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Math Expression Engine Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the math expression engine driver that will be
	| utilized by the math manager.
	|
	| Supported: "bc", "system"
	|
	*/

	'driver' => 'bc',

    /*
	|--------------------------------------------------------------------------
	| Additional Expression Engine Drivers
	|--------------------------------------------------------------------------
	|
	| This option allows you to register additional expression engines with the
	| math manager. Using this is not required, but is the only way to link
	| drivers by a name.
	|
	| For example:
	| 'bc' => '\Stillat\Common\Math\ExpressionEngines\BinaryCalculatorExpressionEngine',
	|
	*/

    'expressionEngines' => [

    ],

	/*
	|--------------------------------------------------------------------------
	| Calculation Precision
	|--------------------------------------------------------------------------
	|
	| The number of decimal places all mathematical operations should be
	| expressed to.
	|
	*/

	'precision' => 6,

);