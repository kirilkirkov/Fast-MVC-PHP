<?php 

namespace App\Models;

use Core\DB;
use Core\Cache;

class User
{
    public function getUser($id)
    {
        $db = DB::getInstance();
        $q = $db->query()
        ->select('email')
        ->where('id = :id', 'is_confirmed = :is_confirmed')
        ->from('users');

        $result = $db->first($q, [
            'id' => $id, 
            'is_confirmed' => 1
        ]);

        return $result;
    }

    public function getAllSlugs()
    {
        $db = DB::getInstance();
        $q = $db->query()
        ->select('slug')
        ->from('users');

        $result = $db->get($q, []);

        return $result;
    }
}