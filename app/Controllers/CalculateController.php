<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\UserModel;

class CalculateController extends BaseController
{
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {

        try {

            switch ($this->request->getMethod()) {
                case 'get':
                    $data = [
                        'content' => 'home/calculate',
                        'title' => 'Home',
                        'css_critical' => '',
                        'js_critical' => '
                            <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                            <script src="app/cal.js"></script>
                        '
                    ];

                    $data['line_user'] = session()->get('line_user');

                    echo view('/app', $data);
                    break;

                case 'post':
                    $status = 500;
                    $response['success'] = 0;
                    $response['message'] = '';
                    $response['data'] = '';

                    $requestPayload = $this->request->getJSON();
                    

                    if (session()->get('customer')) {

                        $customer = $this->customerModel->getCustomerByID(session()->get('customer')->id);

                        if ($customer) {

                            $this->customerModel->updateCustomerByID($customer->id, [
                                'gender' => $requestPayload->gender,
                                'age' => $requestPayload->age,
                                'weight' => $requestPayload->weight,
                                'height' => $requestPayload->height,
                                'exercise' => $requestPayload->exercise,
                                'target' => $requestPayload->target,
                                'cal_per_day' => $requestPayload->calPerDay,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);

                            $response['data'] = '';
                        }
                    }

                    $status = 200;
                    $response['success'] = 1;

                    return $this->response
                        ->setStatusCode($status)
                        ->setContentType('application/json')
                        ->setJSON($response);

                    break;
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }
}
