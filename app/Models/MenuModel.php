<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class MenuModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getMenuAll()
    {
        $builder = $this->db->table('menus');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getMenuByID($id)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertMenu($data)
    {
        $builder = $this->db->table('menus');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateMenuByID($id, $data)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteMenuByID($id)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->delete();
    }

    public function getMenuByUserID($userID)
    {
        $builder = $this->db->table('menus');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }
  
    
    public function getMenuTodayByCustomerID($customerID)
    {
        $sql = "
            SELECT * 
            FROM menus 
            WHERE customer_id = '$customerID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getTotalCalTodayByCustomerID($customerID)
    {
        $sql = "
            SELECT SUM(cal) AS cal_today
            FROM menus 
            WHERE customer_id = '$customerID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }
}
