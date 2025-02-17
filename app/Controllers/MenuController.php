<?php

namespace App\Controllers;

use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class MenuController extends BaseController
{
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function report()
    {
        $data = [
            'content' => 'menu/report',
            'title' => 'Menu',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('/app/menu/report.js') . '"></script>
            '
        ];

        $data['userMenusToday'] = $this->userMenuModel->getUserMenuTodayByUserID(session()->get('user')->id);
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function update()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $menuID = $data->menu_id;
            $newCal = $data->cal;

            $update = $this->userMenuModel->updateUserMenuByID($menuID, [
                'calories' => $newCal,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($update) {

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
            px($e->getMessage());
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
            $menuID = $data->menu_id;

            $delete = $this->userMenuModel->deleteMenuByID($menuID);

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
