<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class HomeController extends BaseController
{
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
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
        if (session()->get('user')) {

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

            $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
            $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

            echo view('/app', $data);
        } else {
            return redirect()->to($this->Auth());
        }
    }

    public function logout()
    {
        try {

            session()->destroy();

            return redirect()->to('/');
        } catch (\Exception $e) {
            //            echo $e->getMessage();
        }
    }
}
