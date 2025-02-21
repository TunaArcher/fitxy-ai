<?php

namespace App\Controllers;

use App\Models\FriendModel;
use App\Models\userModel;
use CodeIgniter\Controller;

class LineLoginController extends Controller
{

    private FriendModel $friendModel;
    private userModel $userModel;

    public function __construct()
    {
        $this->userModel = new userModel();
        $this->friendModel = new FriendModel();
    }

    public function callback()
    {
        // ตรวจสอบว่ามี code และ state ส่งกลับมาหรือไม่
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        if (!$code || !$state) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request: Missing parameters'
            ]);
        }

        // ตรวจสอบว่า state ที่ได้รับมาตรงกับที่ส่งไปตอนแรกหรือไม่ (ป้องกัน CSRF)
        if ($state !== session()->get('oauth_state')) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'State does not match! Possible CSRF attack.'
            ]);
        }

        // แลกเปลี่ยน Code เป็น Access Token
        $token = $this->getAccessToken($code);

        if (!$token) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to get access token'
            ]);
        }

        // ใช้ Access Token ดึงข้อมูลโปรไฟล์ผู้ใช้
        $userInfo = $this->getUserProfile($token);

        if (!$userInfo) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to get user profile'
            ]);
        }

        // ตรวจสอบหรือสร้างบัญชีผู้ใช้
        $user = $this->getOrCreateUser($userInfo);

        if ($user) {

            session()->set('user', $user);
            session()->set('isUserLoggedIn', true);

            if (session()->has('invite_code')) {

                // ตรวจสอบว่ามี invite_code ใน session หรือไม่
                $inviteCode = session()->get('invite_code');

                // แก้บัคเจ้าของลิงก์คลิกเอง
                if ($inviteCode == $user->id) return redirect()->to('/');

                // แก้บัคเข้าแล้วเข้าอีก
                $alreadyFriend = $this->friendModel->getAlreadyFriend($user->id, $inviteCode);
                if ($alreadyFriend) return redirect()->to('/');

                if ($inviteCode != $user->id) {

                    $friendID = $inviteCode;

                    // หากมี invite_code แสดงว่ามาจากคำเชิญ ให้ redirect ไปหน้า /friends

                    $this->friendModel->insertFriend([
                        'user_id' => $user->id,
                        'friend_id' => $friendID,
                    ]);

                    $this->friendModel->insertFriend([
                        'user_id' => $friendID,
                        'friend_id' => $user->id,
                    ]);

                    session()->remove('invite_code'); // ล้าง session

                    return redirect()->to('/');
                }
            }

            // ถ้าไม่มี invite_code ให้ไปหน้าแรก
            return redirect()->to('/');
        }

        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'Failed to login user'
        ]);
    }

    private function getAccessToken($code)
    {
        $client_id = getenv('LINE_CLIENT_ID');
        $client_secret = getenv('LINE_CLIENT_SECRET');
        $redirect_uri = base_url('/callback'); // ต้องตรงกับที่ตั้งค่าใน LINE Developers

        $url = "https://api.line.me/oauth2/v2.1/token";

        $data = [
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $redirect_uri,
            "client_id" => $client_id,
            "client_secret" => $client_secret
        ];

        $client = \Config\Services::curlrequest();
        $response = $client->request('POST', $url, [
            'form_params' => $data,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);

        $result = json_decode($response->getBody(), true);

        return $result['access_token'] ?? null;
    }

    private function getUserProfile($accessToken)
    {
        $url = "https://api.line.me/v2/profile";

        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $accessToken]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getOrCreateUser($userInfo)
    {

        $user = $this->userModel->getUserByUID($userInfo['userId']);

        if (!$user) {

            $userID = $this->userModel->insertUser([
                'uid' => $userInfo['userId'],
                'name' => $userInfo['displayName'],
                'profile' => $userInfo['pictureUrl']
            ]);

            return $this->userModel->getUserByID($userID);
        }

        return $user;
    }
}
