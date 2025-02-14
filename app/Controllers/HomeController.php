<?php

namespace App\Controllers;

use App\Models\MenuModel;

class HomeController extends BaseController
{
    private MenuModel $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    private function Auth()
    {
        // สร้างค่า state แบบสุ่ม
        $state = bin2hex(random_bytes(16));

        // เก็บค่า state ไว้ใน Session โดยใช้ CI4
        session()->set('oauth_state', $state);

        // ใช้ค่า $state ใน URL ของ LINE Login
        $line_login_url = "https://access.line.me/oauth2/v2.1/authorize?" . http_build_query([
            "response_type" => "code",
            "client_id" => getenv('LINE_CLIENT_ID'),
            "redirect_uri" => base_url('/callback'),
            "scope" => "profile openid email",
            "state" => $state
        ]);

        return $line_login_url;
    }

    public function index()
    {
        if (session()->get('customer')) {

            $data = [
                'content' => 'home/index',
                'title' => 'Home',
                'css_critical' => '',
                'js_critical' => '
                    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                    <script src="app/home.js"></script>
                    <script src="assets/js/fitness/fitness-dashboard.js"></script>
                '
            ];

            $data['menuToday'] = $this->menuModel->getMenuTodayByCustomerID(session()->get('customer')->id);
            $data['calToDay'] = $this->menuModel->getTotalCalTodayByCustomerID(session()->get('customer')->id)->cal_today;

            echo view('/app', $data);
        } else {
            return redirect()->to($this->Auth());
        }
    }

    public function report()
    {
        $data = [
            'content' => 'home/report',
            'title' => 'Home',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/report.js"></script>
            '
        ];

        $data['menuToday'] = $this->menuModel->getMenuTodayByCustomerID(session()->get('customer')->id);
        $data['calToDay'] = $this->menuModel->getTotalCalTodayByCustomerID(session()->get('customer')->id)->cal_today;

        echo view('/app', $data);
    }

    public function logout()
    {
        try {

            session()->destroy();

            return redirect()->to('/login');
        } catch (\Exception $e) {
            //            echo $e->getMessage();
        }
    }

    public function menuUpdate()
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

            $update = $this->menuModel->updateMenuByID($menuID, [
                'cal' => $newCal,
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

    public function menuDelete()
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

            $delete = $this->menuModel->deleteMenuByID($menuID);

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
