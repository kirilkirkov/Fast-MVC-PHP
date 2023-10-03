<?php

namespace App\Controllers;

use Core\Paginator;
use App\Models\User;

class WelcomeController extends \Core\Controller
{
    public const LAYOUT = 'layouts/main';

    public function index($page = 1)
    {
        $singletonService  = app()->getService('SingletonService');
        $bindService = app()->getService('BindService');

        // $paginator = new Paginator(10, $page, User::getCountAll(), '/page/');

        $this->view->render('welcome', [
            'name' => 'by Kiril Kirkov',
            // 'paginator' => $paginator,
        ]);
    }
}