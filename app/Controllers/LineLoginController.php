<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class LineLoginController extends Controller
{

    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
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
        $customer = $this->getOrCreateCustomer($userInfo);

        if ($customer) {
            session()->set('customer', $customer);
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

    public function getOrCreateCustomer($userInfo)
    {

        $customer = $this->customerModel->getCustomerByUID($userInfo['userId']);

        if (!$customer) {

            $customerID = $this->customerModel->insertCustomer([
                'uid' => $userInfo['userId'],
                'name' => $userInfo['displayName'],
                'profile' => $userInfo['pictureUrl']
            ]);

            return $this->customerModel->getCustomerByID($customerID);
        }

        return $customer;
    }
}
