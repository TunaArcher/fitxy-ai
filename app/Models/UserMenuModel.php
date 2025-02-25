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
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE() ORDER BY created_at DESC;
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

    public function getMacronutrientsByUserIDAndDate($userID, $date)
    {
        $sql = "
            SELECT 
                COALESCE(SUM(calories), 0) AS calories,
                COALESCE(SUM(carbohydrates), 0) AS carbs,
                COALESCE(SUM(protein), 0) AS protein,
                COALESCE(SUM(fat), 0) AS fat
            FROM user_menus 
            WHERE user_id = '$userID' AND DATE(created_at) = '$date';
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getCalories7daysByUserID($userID, $date)
    {
        $sql = "
            WITH RECURSIVE last_7_days AS (
                SELECT ? AS record_date
                UNION ALL
                SELECT DATE_SUB(record_date, INTERVAL 1 DAY)
                FROM last_7_days
                WHERE record_date > DATE_SUB(?, INTERVAL 6 DAY)
            )
            SELECT 
                lsd.record_date,
                COALESCE(SUM(um.calories), 0) AS calories_today,
                COALESCE(SUM(um.carbohydrates), 0) AS carbs_today,
                COALESCE(SUM(um.protein), 0) AS protein_today,
                COALESCE(SUM(um.fat), 0) AS fat_today
            FROM last_7_days lsd
            LEFT JOIN user_menus um 
                ON DATE(um.created_at) = lsd.record_date 
                AND um.user_id = ?
            GROUP BY lsd.record_date
            ORDER BY lsd.record_date ASC;
        ";

        // โดยใช้ $date เป็นพารามิเตอร์สำหรับวันเริ่มต้นในทั้งสองตำแหน่ง และ $userID ในตำแหน่งที่สาม
        $query = $this->db->query($sql, [$date, $date, $userID]);

        return $query->getResult();
    }

    public function getUserMenuByUserIDAndDate($userID, $date)
    {
        $sql = "
            SELECT * 
            FROM user_menus 
            WHERE user_id = '$userID' AND DATE(created_at) = '$date' ORDER BY created_at DESC;
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
