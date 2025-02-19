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

            $user = $this->userModel->getUserByUID('Ubb5e0e3569de3b8dfc0b3d0fb55e3440');

            session()->set('user', $user);
            session()->set('isUserLoggedIn', true);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
