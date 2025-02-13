<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use Hashids\Hashids;

class CheckPermissions implements FilterInterface
{

    protected $customerModel;
    public function __construct()
    {
        $this->customerModel = new \App\Models\CustomerModel();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (getenv('CI_ENVIRONMENT') === 'development') {

            $customer = $this->customerModel->getCustomerByUID('U8bf2cbdb6cbbdb8709dc268512abd4a3');

            session()->set('customer', $customer);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
