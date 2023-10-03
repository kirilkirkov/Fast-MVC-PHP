<?php 

namespace App\Models;

use Core\DB;
use Core\Cache;
use Core\Request;

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

    public static function getCountAll()
    {
        $db = DB::getInstance();

        $prepareParams = [];
        $where = '';

        $getParams = (new Request())->getParams();
        if(isset($getParams['email']) && trim($getParams['email'] != '')) {
            $where = 'WHERE (users.email LIKE :email)';
            $prepareParams = [
                'email' => '%'.trim($getParams['email']).'%',
            ];
        }

        $result = $db->first("SELECT count(id) as cnt FROM users {$where}", $prepareParams);
        return $result['cnt'];
    }
}