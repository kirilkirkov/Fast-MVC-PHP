<?php 

namespace App\Models;

use Core\DB;
use Core\Cache;

class User
{
    public function getUser($id)
    {
        if(Cache::get("user_{$id}")) {
            return Cache::get("user_{$id}");
        }

        $db = DB::getInstance();
        $q = $db->query()
        ->select('email')
        ->where('id = :id', 'email = :email')
        ->from('users');

        $result = $db->first($q, [
            'id' => 10, 
            'email' => 'user-10@user-10.email'
        ]);

        Cache::set("user_{$id}", $result);

        return $result;
    }
}