<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class CalculateController extends BaseController
{
    private UserModel $userModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function index()
    {

        try {

            switch ($this->request->getMethod()) {
                case 'get':
                    $data = [
                        'content' => 'home/calculate',
                        'title' => 'Home',
                        'css_critical' => '',
                        'js_critical' => '
                            <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                            <script src="app/cal.js"></script>
                        '
                    ];

                    $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
                    $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

                    echo view('/app', $data);

                    break;

                case 'post':
                    $status = 500;
                    $response['success'] = 0;
                    $response['message'] = '';
                    $response['data'] = '';

                    $requestPayload = $this->request->getJSON();


                    if (session()->get('user')) {

                        $user = $this->userModel->getUserByID(session()->get('user')->id);

                        if ($user) {

                            $this->userModel->updateUserByID($user->id, [
                                'gender' => $requestPayload->gender,
                                'age' => $requestPayload->age,
                                'weight' => $requestPayload->weight,
                                'height' => $requestPayload->height,
                                'exercise' => $requestPayload->exercise,
                                'target' => $requestPayload->target,
                                'cal_per_day' => $requestPayload->calPerDay,
                                'maintenanceCal' => $requestPayload->maintenanceCal,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);

                            session()->set('user', $user);

                            $response['data'] = '';
                        }
                    }

                    $status = 200;
                    $response['success'] = 1;

                    return $this->response
                        ->setStatusCode($status)
                        ->setContentType('application/json')
                        ->setJSON($response);

                    break;
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }
}
