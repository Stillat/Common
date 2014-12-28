<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Sorting Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the sorting driver that will be utilized by the
	| sorting manager.
	|
	| Supported: "native", "quick"
	|
	*/

	'driver' => 'native',

	/*
	|--------------------------------------------------------------------------
	| Additional Sorting Drivers
	|--------------------------------------------------------------------------
	|
	| This option allows you to register additional sorting drivers with the
	| sorting manager. Using this is not required, but is the only way to link
	| drivers by a name.
	|
	| Sorting drivers are an associative array, where the key is the name of the
	| driver and the value is the fully qualified class name of the driver.
	|
	| For example:
	| 'native' => '\Stillat\Common\Collections\Sorting\Drivers\NativeQuickSorter',
	|
	*/

	'sortingDrivers' => [

    ],

);