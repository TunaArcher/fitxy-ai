<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\WorkoutModel;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class WorkoutController extends BaseController
{
    private WorkoutModel $workoutModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {

        $this->workoutModel = new WorkoutModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function index()
    {
        $data = [
            'content' => 'workout/index',
            'title' => 'Workout',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/workout/index.js') . '"></script>
            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();
        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);
        $data['caloriesToDay'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function add()
    {
        $data = [
            'content' => 'workout/add',
            'title' => 'Workout',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/workout/index.js') . '"></script>
            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();
        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);
        $data['caloriesToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function save()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;
            
            $data = $this->request->getJSON();

            $userWorkout = $this->userWorkoutModel->insertUserWorkout([
                'user_id' => session()->get('user')->id,
                'workout_id' => $data->id,
                'title' => $data->title,
                'calories' => $data->calories,
                'time' => $data->time,
            ]);

            if ($userWorkout) {

                $response = [
                    'success' => 1,
                    'message' => 'สำเร็จ',
                ];

                $status = 200;
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            // px($e->getMessage() . ' ' . $e->getLine());
        }
    }

    public function delete()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $workoutID = $data->workout_id;

            $delete = $this->userWorkoutModel->deleteUserWorkoutByID($workoutID);

            if ($delete) {

                $response = [
                    'success' => 1,
                    'message' => 'สำเร็จ',
                ];

                $status = 200;
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
        }
    }
}
