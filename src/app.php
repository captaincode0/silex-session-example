<?php

	use Silex\Application;
	use Silex\Provider\SessionServiceProvider;
	use Silex\Provider\TwigServiceProvider;
	use Silex\Provider\UrlGeneratorServiceProvider;
	use Silex\Provider\ValidatorServiceProvider;

	$app = new Application();

	$app["debug"] = true;
	$app->register(new UrlGeneratorServiceProvider());
	$app->register(new SessionServiceProvider());
	$app->register(new ValidatorServiceProvider());
	$app->register(new TwigServiceProvider(), [
		"twig.path" => __dir__."/../views"
	]);

	return $app;