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

        // $sql = "
        //     SELECT 
        //         workouts.icon AS icon
        //         user_workouts.id,
        //         user_workouts.calories AS calories,
        //         user_workouts.time AS time,
        //         user_workouts.title AS title,
        //         user_workouts.created_at 
        //     FROM user_workouts 
        //     JOIN workouts ON user_workouts.workout_id = workouts.id
        //     WHERE user_workouts.user_id = '$userID' AND DATE(user_workouts.created_at) = CURDATE();
        // ";

        $sql = "
            SELECT 
                workouts.id,
                workouts.icon,
                user_workouts.user_id,
                user_workouts.workout_id,
                user_workouts.title AS user_workout_title,
                user_workouts.time,
                user_workouts.calories,
                user_workouts.created_at
            FROM workouts 
            JOIN user_workouts ON user_workouts.workout_id = workouts.id
            WHERE user_workouts.user_id = '$userID' AND DATE(user_workouts.created_at) = CURDATE() ORDER BY user_workouts.created_at DESC;
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

    public function getWorkoutSummaryByUserIDAndDate($userID, $date)
    {
        $sql = "
            SELECT 
                COALESCE(SUM(time), 0) AS time,
                COALESCE(SUM(calories), 0) AS calories
            FROM user_workouts 
            WHERE user_id = '$userID' AND DATE(created_at) = '$date';
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getUserWorkoutByUserIDAndDate($userID, $date)
    {

        $sql = "
            SELECT 
                workouts.id,
                workouts.icon,
                user_workouts.user_id,
                user_workouts.workout_id,
                user_workouts.title AS user_workout_title,
                user_workouts.time,
                user_workouts.calories,
                user_workouts.created_at
            FROM workouts 
            JOIN user_workouts ON user_workouts.workout_id = workouts.id
            WHERE user_workouts.user_id = '$userID' AND DATE(user_workouts.created_at) = '$date' ORDER BY user_workouts.created_at DESC;
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
