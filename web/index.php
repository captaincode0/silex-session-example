<?php
	ini_set("display_errors", 1);
	error_reporting(E_ALL);

	require __dir__."/../vendor/autoload.php";
	$app = require __dir__."/../src/app.php";
	require __dir__."/../src/controllers/ControllersLoader.php";
	require __dir__."/../src/mount-controllers.php";
	$app->run();