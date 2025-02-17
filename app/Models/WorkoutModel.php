<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class WorkoutModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getWorkoutAll()
    {
        $builder = $this->db->table('workouts');

        return $builder
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResult();
    }

    public function getWorkoutByID($id)
    {
        $builder = $this->db->table('workouts');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertWorkout($data)
    {
        $builder = $this->db->table('workouts');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateWorkoutByID($id, $data)
    {
        $builder = $this->db->table('workouts');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteWorkoutByID($id)
    {
        $builder = $this->db->table('workouts');

        return $builder->where('id', $id)->delete();
    }

    public function getWorkoutByUserID($userID)
    {
        $builder = $this->db->table('workouts');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }
  
    
    public function getWorkoutTodayByUserID($userID)
    {
        $sql = "
            SELECT * 
            FROM workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getTotalCalTodayByUserID($userID)
    {
        $sql = "
            SELECT SUM(cal) AS cal_today
            FROM workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }
}
