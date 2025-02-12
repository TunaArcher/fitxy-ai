<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class LineLoginController extends Controller
{
    public function callback()
    {
        session_start(); // ใช้ session เพื่อเก็บ state

        // ตรวจสอบว่ามี code และ state ส่งกลับมาหรือไม่
        if (!isset($_GET['code']) || !isset($_GET['state'])) {
            die('Invalid request');
        }

        // ตรวจสอบว่า state ที่ได้รับมาตรงกับที่ส่งไปตอนแรกหรือไม่ (ป้องกัน CSRF)
        if ($_GET['state'] !== $_SESSION['oauth_state']) {
            die('State does not match! Possible CSRF attack.');
        }

        $code = $_GET['code'];

        // แลกเปลี่ยน Code เป็น Access Token
        $token = $this->getAccessToken($code);

        if (!$token) {
            die('Failed to get access token');
        }

        // ใช้ Access Token ดึงข้อมูลโปรไฟล์ผู้ใช้
        $userInfo = $this->getUserProfile($token);

        // บันทึกข้อมูลผู้ใช้ หรือทำการ Login
        $_SESSION['line_user'] = $userInfo;

        echo "Login Successful!";
        print_r($userInfo); // แสดงข้อมูลผู้ใช้ (ทดสอบ)
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
}
