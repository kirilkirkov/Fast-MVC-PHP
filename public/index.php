<?php

define('FAST_MVC_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Register Helpers
|--------------------------------------------------------------------------
*/

require __DIR__.'/../core/Helpers.php';

/*
|--------------------------------------------------------------------------
| Call The App Register Services And Bindings
|--------------------------------------------------------------------------
*/
require __DIR__.'/../core/App.php';
\Core\App::getInstance();

/*
|--------------------------------------------------------------------------
| Register The Core Request Loader
|--------------------------------------------------------------------------
|
| Handle requests and load routes
|
*/

require __DIR__.'/../core/Request.php';
$request = new Core\Request();
$request->handle();
$request->appEnd();