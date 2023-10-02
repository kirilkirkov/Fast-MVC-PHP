<?php

namespace App\Controllers;

class WelcomeController extends \Core\Controller
{
    public const LAYOUT = 'layouts/main';

    public function index()
    {
        $singletonService  = app()->getService('SingletonService');
        $bindService = app()->getService('BindService');

        $this->view->render('welcome', [
            'name' => 'by Kiril Kirkov'
        ]);
    }
}