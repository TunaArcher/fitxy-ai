<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class CheckPermissions implements FilterInterface
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function before(RequestInterface $request, $arguments = null)
    {

        if (getenv('CI_ENVIRONMENT') === 'development') {

            $user = $this->userModel->getUserByUID('Ucac64382c185fd8acd69438c5af15935');

            session()->set('user', $user);
            session()->set('isUserLoggedIn', true);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
