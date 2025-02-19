<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use Hashids\Hashids;

class UserAuth implements FilterInterface
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // ตรวจสอบการเข้าสู่ระบบ
        if (!session()->get('isUserLoggedIn')) {

            session()->destroy();

            session()->setFlashdata(['session_expired' => 'เซ็นซันหมดอายุ กรุณาล็อคอินอีกครั้ง']);

            return redirect()->to('/');
        }

        // ตรวจสอบว่าผู้ใช้มีอยู่ในระบบหรือไม่
        $user = $this->userModel->getUserByID(session()->get('user')->id);
        if (!$user) {

            session()->destroy();

            session()->setFlashdata(['session_expired' => 'เซ็นซันหมดอายุ กรุณาล็อคอินอีกครั้ง']);

            return redirect()->to('/');
        }

        if ($user) {
            $user = $this->userModel->getUserByUID(session()->get('user')->uid);

            session()->set('user', $user);
            session()->set('isUserLoggedIn', true);
        }

        return null; // อนุญาตให้ผ่าน
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
