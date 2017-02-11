#silex-session-example

This silex session example repository contains the base code for user authentication in one silex application.

##Routes

Route path|Description
---|---
/|Root path to launch the public content.
/private|Private user zone, you'll need user auth to enter.
/middleware/auth|Auth middleware to validate the user input data.

##How to handle sessions correctly in silex

First you need to know that sessions in this framework depends of `SessionServiceProvider`, but you need to configure it as the following piece of code:

```php
    use Silex\Provider\SessionServiceProvider;

    $app->register(new SessionServiceProvider(),[ 
        //PROVIDER CONFIGURATIONS
    ]);
```

Secondly you need the middleware after to start the current session and get the flag or status `is_auth`.

```php
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class MyRestrictedAppZone implements ControllerProviderInterface{
        public function connect(Application $app){
            $controllers = $app["controllers_factory"];

            $controllers->get("/shop", function(Request $request, $session) use($app){
                $session = $request->getSession();

                //do something with the current session
                $user_token = $session->get("objects.user")->getToken();

                $app["db.mysql"]->fetchAssoc("select ip, useragent from usersessions where token=?", [$user_token]);
            })
                ->bind("myzone.shop");

            //global before middleware
            $controllers->before(function(Request $request, Application $app){
                $request->getSession()->start();
            });

            //global after middleware to put the raw data and change the headers
            $controllers->after(function(Request $request, Response $response) use($app){
                $response->headers->set("content-type", "text/html");
            });
            return $controllers;
        }
    }
```

##How to close one session correctly in silex

Accord to the oficial documentation of Symfony framework, exists one method in `SessionInterface` called `invalidate`, that clears the current session to set new values.

```php

    $app->get("/logout", function(Request $request) use($app){
        //get the current session
        $session = $request->getSession();

        //check if the user is auth
        if($session->get("is_auth")){
            //destroy the current session
            $session->invalidate();
            //redirect to login session
            return $app->redirect($app["url_generator"]->redirect("restricted.home"));
        }
        //is the session is not active then redirect into the home of restricted area
        else
            return $app->redirect($app["url_generator"]->redirect("restricted.home"));
    });
```