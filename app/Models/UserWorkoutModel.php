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
            SELECT 
                user_workouts.id,
                user_workouts.calories AS calories,
                user_workouts.time AS time,
                workouts.title AS title,
                workouts.icon AS icon
            FROM user_workouts 
            JOIN workouts ON user_workouts.workout_id = workouts.id
            WHERE user_workouts.user_id = '$userID' AND DATE(user_workouts.created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getTotalCaloriesTodayByUserID($userID)
    {
        $sql = "
            SELECT SUM(calories) AS calories_today
            FROM user_workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = CURDATE();
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }
}
