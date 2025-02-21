<?php

namespace App\Controllers;

use App\Models\FriendModel;
use App\Models\UserMenuModel;
use App\Models\UserModel;
use App\Models\UserWorkoutModel;

class FriendController extends BaseController
{
    private FriendModel $friendModel;
    private UserModel $userModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->friendModel = new FriendModel();
        $this->userModel = new UserModel();
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
        $data = [
            'content' => 'friend/index',
            'title' => 'Friend',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/friend/index.js"></script>
                <script src="assets/js/fitness/fitness-dashboard.js"></script>
            '
        ];

        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function show($userID)
    {
        $data = [
            'content' => 'friend/show',
            'title' => 'Friend',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/friend/show.js"></script>
            '
        ];

        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        $data['friends'] = $this->friendModel->getFriendByUserID($userID);

        echo view('/app', $data);
    }

    public function letsGo($userID, $slug)
    {

        $data = [
            'content' => 'friend/letsgo',
            'title' => 'Friend',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/friend/letsgo.js"></script>
            '
        ];

        $data['user'] = $this->userModel->getUserByID($userID);

        echo view('friend/letsgo', $data);
    }

    public function ok($userID)
    {
        $inviteCode =  $userID;

        session()->set('invite_code', $inviteCode);

        return redirect()->to($this->Auth());
    }
}
