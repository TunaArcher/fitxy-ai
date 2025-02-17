<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class UserMenuModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getUserMenuAll()
    {
        $builder = $this->db->table('user_menus');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getUserMenuByID($id)
    {
        $builder = $this->db->table('user_menus');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertUserMenu($data)
    {
        $builder = $this->db->table('user_menus');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateUserMenuByID($id, $data)
    {
        $builder = $this->db->table('user_menus');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteUserMenuByID($id)
    {
        $builder = $this->db->table('user_menus');

        return $builder->where('id', $id)->delete();
    }

    public function getUserMenuByUserID($userID)
    {
        $builder = $this->db->table('user_menus');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }
  
    
    public function getUserMenuTodayByUserID($userID)
    {
        $sql = "
            SELECT * 
            FROM user_menus 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getTotalCaloriesTodayByUserID($userID)
    {
        $sql = "
            SELECT SUM(calories) AS calories_today
            FROM user_menus 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }
}
