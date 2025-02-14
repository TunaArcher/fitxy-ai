<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class FoodTableModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getFoodTableAll()
    {
        $builder = $this->db->table('food_tables');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getFoodTableByID($id)
    {
        $builder = $this->db->table('food_tables');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertFoodTable($data)
    {
        $builder = $this->db->table('food_tables');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateFoodTableByID($id, $data)
    {
        $builder = $this->db->table('food_tables');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteFoodTableByID($id)
    {
        $builder = $this->db->table('food_tables');

        return $builder->where('id', $id)->delete();
    }

    public function getFoodTableByCustomerID($customerID)
    {
        $builder = $this->db->table('food_tables');

        return $builder
            ->where('customer_id', $customerID)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getRow();
    }

    public function getFoodTableTodayByCustomerID($customerID)
    {
        $sql = "
            SELECT * 
            FROM food_tables 
            WHERE customer_id = '$customerID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function updateFoodTableByCustomerID($customerID, $data)
    {
        $builder = $this->db->table('food_tables');

        return $builder->where('customer_id', $customerID)->update($data);
    }
    
}
