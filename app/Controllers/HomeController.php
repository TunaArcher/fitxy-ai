<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\SubscriptionModel;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\TeamMemberModel;
use App\Models\TeamModel;
use App\Models\TeamSocialModel;
use App\Models\UserModel;
use App\Models\UserSocialModel;

class HomeController extends BaseController
{
    private SubscriptionModel $subscriptionModel;
    private TeamModel $teamModel;
    private TeamSocialModel $teamSocialModel;
    private TeamMemberModel $teamMemberModel;
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;
    private UserModel $userModel;
    private UserSocialModel $userSocialModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();
        $this->teamModel = new TeamModel();
        $this->teamMemberModel = new TeamMemberModel();
        $this->teamSocialModel = new TeamSocialModel();
        $this->subscriptionModel = new SubscriptionModel();
        $this->userSocialModel = new UserSocialModel();
        $this->userModel = new UserModel();
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

            echo view('/app', $data);
        } else {
            return redirect()->to($this->Auth());
        }
    }

    public function calculate()
    {
        $data = [
            'content' => 'home/calculate',
            'title' => 'Home',
            'css_critical' => '',
            'js_critical' => '<script src="app/cal.js"></script>'
        ];

        $data['line_user'] = session()->get('line_user');

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
}
