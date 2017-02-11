<?php
	namespace MyApp\Http\Controllers;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	class PrivateUserZoneControllers implements ControllerProviderInterface{
		public function connect(Application $app){
			$controllers = $app["controllers_factory"];

			$controllers->get("/", function() use($app){
				return "<h1>Private zone home page</h1>";
			})
				->bind("private.home");

			$controllers->get("/profile", function(Request $request) use($app){
				$username = $request->getSession()->get("username");
				return "<h1>Private zone user profile</h1><p><b>Username:</b> $username</p><br>";
			})
				->bind("private.profile");

			$controllers->before(function(Request $request, Application $app){
				$request->getSession()->start();

				if(!$request->getSession()->get("is_auth"))
					return $app->redirect($app["url_generator"]->generate("login"));
			});

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content/type", "text/html");
			});

			return $controllers;
		}	
	}