<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\WorkoutModel;
use App\Models\UserWorkoutModel;

class WorkoutController extends BaseController
{
    private WorkoutModel $workoutModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {

        $this->workoutModel = new WorkoutModel();
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
                <script src="app/workout/index.js"></script>            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();
        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);

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
                <script src="app/workout/add.js"></script>            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();
        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);

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
}
