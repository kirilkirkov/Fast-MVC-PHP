<?php

namespace App\Controllers;

class WelcomeController extends \Core\Controller
{
    public const LAYOUT = 'layouts/main';

    public function index()
    {
        $this->view->render('welcome');
    }
}