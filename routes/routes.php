<?php
use Core\Route;

/**
 * More info about how to define a routes
 * https://github.com/nikic/FastRoute
 * 
 * include another files if wants.
 */

Route::add('GET', '/', 'WelcomeController@index');
Route::add('GET', '/user/{id:\d+}', 'Users\UserController@index');