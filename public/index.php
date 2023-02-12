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
| Register The Core Request Loader
|--------------------------------------------------------------------------
|
| Handle requests and load routes
|
*/

require __DIR__.'/../core/Request.php';
$request = new Core\Request();
$request->handle();