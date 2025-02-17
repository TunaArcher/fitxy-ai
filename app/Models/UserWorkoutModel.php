<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class UserWorkoutModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getUserWorkoutAll()
    {
        $builder = $this->db->table('user_workouts');

        return $builder
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResult();
    }

    public function getUserWorkoutByID($id)
    {
        $builder = $this->db->table('user_workouts');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertUserWorkout($data)
    {
        $builder = $this->db->table('user_workouts');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateUserWorkoutByID($id, $data)
    {
        $builder = $this->db->table('user_workouts');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteUserWorkoutByID($id)
    {
        $builder = $this->db->table('user_workouts');

        return $builder->where('id', $id)->delete();
    }

    public function getUserWorkoutByUserID($userID)
    {
        $builder = $this->db->table('user_workouts');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }
  
    
    public function getUserWorkoutTodayByUserID($userID)
    {
        $sql = "
            SELECT * 
            FROM user_workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getTotalCalTodayByUserID($userID)
    {
        $sql = "
            SELECT SUM(cal) AS cal_today
            FROM user_workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }
}
