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
        ->where('id = :id', 'is_confirmed = :is_confirmed')
        ->from('users');

        $result = $db->first($q, [
            'id' => $id, 
            'is_confirmed' => 1
        ]);

        Cache::set("user_{$id}", $result);

        return $result;
    }
}