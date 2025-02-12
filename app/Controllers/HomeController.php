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

    public function index()
    {
        $data = [
            'content' => 'home/index',
            'title' => 'Home',
            'css_critical' => '',
            'js_critical' => ''
        ];

        $data['line_user'] = session()->get('line_user');

        echo view('/app', $data);
    }

    public function register()
    {
        session_start(); // เริ่ม Session เพื่อเก็บค่า state

        if (session()->get('line_user')) {
            return redirect()->to('/');
        }

        $grant_type = "authorization_code";
        $code = "CODE_FROM_LINE";
        $callback_uri = base_url('/callback');
        $client_id = "2006891812";
        $client_secret = "9fb3a0f44a76c91f40bc2971b57e1066";

        // สร้างค่า state แบบสุ่ม
        $state = bin2hex(random_bytes(16));

        // เก็บค่า state ไว้ใน Session เพื่อใช้ตรวจสอบภายหลัง
        $_SESSION['oauth_state'] = $state;

        // ใช้ค่า $state ใน URL ของ LINE Login
        $line_login_url = "https://access.line.me/oauth2/v2.1/authorize?" . http_build_query([
            "response_type" => "code",
            "client_id" => getenv('LINE_CLIENT_ID'),
            "redirect_uri" =>  base_url('/callback'),
            "scope" => "profile openid email",
            "state" => $state
        ]);

        // // แสดงปุ่ม Login
        // echo '<a href="' . $line_login_url . '">Login with LINE</a>';

        return redirect()->to($line_login_url);
    }
}
