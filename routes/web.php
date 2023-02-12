<?php
use Core\Route;

Route::add('GET', '/', 'WelcomeController@index');
Route::add('GET', '/asd', 'Other\AsdController@index');