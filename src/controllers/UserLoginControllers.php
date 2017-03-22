<?php
	namespace MyApp\Http\Controllers;	

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use MyApp\Entity\User;

	class UserLoginControllers implements ControllerProviderInterface{
		public function connect(Application $app){
			$controllers = $app["controllers_factory"];

			$controllers->post("/login", function(Request $request) use($app){
				$username = $request->get("username");
				$password = $request->get("password");

				if($username === "admin" & $password === "admin"){
					$app["session"]->set("username", $username);
					$app["session"]->set("is_auth", true);
					return new Response("User successfuly logged", 200);
				}

				return new Response("The user and the password are incorrect", 403);
			})
				->before(function(Request $request, Application $app){
					//check if the session is started
					if($request->getSession()->isStarted())
						return $app->redirect($app["url_generator"]->generate("private.profile"));

					//validate all the fields
					/*$username = $request->get("username");
					$password = $request->get("password");

					if(!preg_match("/^[a-zA-Z_0-9]+$/", $username)
						|| !preg_match("/^[a-zA-Z_0-9]+$/", $password))
						return new Response("The user name or the password are wrong.", 403);*/

					$user = new User($request->get("username"), $request->get("password"));

					$errors = $app["validator"]->validate($user);

					if(count($errors) > 0){
						$error_message = "";
						foreach($errors as $error)
							$error_message .= $error_message."<br>";

						return new Response($error_message, 403);
					}
				})
				->bind("middleware.auth.login");

			$controllers->get("/logout", function(Request $request) use($app){
				//if the session is started clear all the data and destroy
				if($request->getSession()->get("is_auth")){
					//invalidate the current session
					$request->getSession()->invalidate();

					return "<h1>Logout successful </h1> return to <a href='{$app['url_generator']->generate('login')}'>login</a>";
				}
				else
					//if the session is not started then redirect to the main page
					return $app->redirect($app["url_generator"]->generate("index"));
			})
				->before(function(Request $request, Application $app){
					$request->getSession()->start();
				})
				->bind("middleware.auth.logout");

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content-type", "text/html");
			});

			return $controllers;
		}
	}