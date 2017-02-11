<?php
	
	use MyApp\Http\Controllers\MainHTTPControllers;
	use MyApp\Http\Controllers\PrivateUserZoneControllers;
	use MyApp\Http\Controllers\UserLoginControllers;

	$app->mount("/", new MainHTTPControllers());
	$app->mount("/private", new PrivateUserZoneControllers());
	$app->mount("/middleware/auth", new UserLoginControllers());