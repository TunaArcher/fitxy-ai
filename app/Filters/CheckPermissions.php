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
         
            $user = $this->userModel->getUserByUID('U8bf2cbdb6cbbdb8709dc268512abd4a3');

            session()->set('user', $user);
            session()->set('isUserLoggedIn', true);
        }

        else {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
