<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class UserFoodTableModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getUserFoodTableAll()
    {
        $builder = $this->db->table('user_food_tables');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getUserFoodTableByID($id)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertUserFoodTable($data)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateUserFoodTableByID($id, $data)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteUserFoodTableByID($id)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder->where('id', $id)->delete();
    }

    public function getUserFoodTableByUserID($userID)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder
            ->where('user_id', $userID)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getRow();
    }

    public function getUserFoodTableTodayByUserID($userID)
    {
        $sql = "
            SELECT * 
            FROM user_food_tables 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function updateUserFoodTableByUserID($userID, $data)
    {
        $builder = $this->db->table('user_food_tables');

        return $builder->where('user_id', $userID)->update($data);
    }
    
}
