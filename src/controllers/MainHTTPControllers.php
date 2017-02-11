<?php

	namespace MyApp\Http\Controllers;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	class MainHTTPControllers implements ControllerProviderInterface{
		public function connect(Application $app){
			$controllers = $app["controllers_factory"];

			$controllers->get("/", function(){
				return "<h1>Index page</h1>";
			})
				->bind("index");

			$controllers->get("/about", function(){
				return "<h1>About page</h1>";
			})
				->bind("about");

			$controllers->get("/contact", function(){
				return "<h1><Contact page/h1>";
			})
				->bind("contact");

			$controllers->get("/shop", function(){
				return "<h1>Shop page</h1>";
			})
				->bind("shop");

			$controllers->get("/login", function(Request $request) use($app){
				if(!$request->getSession()->get("is_auth"))
					return $app["twig"]->render("login.html.twig");

				return $app->redirect($app["url_generator"]->generate("logout"));
			})
				->bind("login");

			$controllers->get("/logout", function() use($app){
				return "<p>Your session is started, actions: <a href='{$app['url_generator']->generate('middleware.auth.logout')}'>logout</a></p>";
			})
				->bind("logout");

			$controllers->before(function(Request $request, Application $app){
				$request->getSession()->start();
			});

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content/type", "text/html");
			});

			return $controllers;
		}
	}