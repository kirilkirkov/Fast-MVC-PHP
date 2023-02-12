<?php

namespace App\Controllers\Users;

use App\Models\User;

class UserController extends \Core\Controller
{
    public function index($id)
    {
        $user = (new User())->getUser($id);
        // here is user
    }
}