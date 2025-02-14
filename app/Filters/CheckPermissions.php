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
        // if (getenv('CI_ENVIRONMENT') === 'development' || getenv('CI_ENVIRONMENT') === 'production') {
        if (getenv('CI_ENVIRONMENT') === 'development') {

            $customer = $this->customerModel->getCustomerByUID('U631d74f9459a3f6b033c6c8a97acf980');

            session()->set('customer', $customer);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
