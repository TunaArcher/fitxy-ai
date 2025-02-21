<?php

namespace App\Controllers;

use App\Models\FriendModel;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class ProfileController extends BaseController
{
    private FriendModel $friendModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->friendModel = new FriendModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function index()
    {
        $data = [
            'content' => 'profile/index',
            'title' => 'Profile',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/profile/index.js"></script>
                <script src="assets/js/fitness/fitness-dashboard.js"></script>
            '
        ];

        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        $data['friends'] = $this->friendModel->getFriendByUserID(session()->get('user')->id);

        echo view('/app', $data);
    }
}
