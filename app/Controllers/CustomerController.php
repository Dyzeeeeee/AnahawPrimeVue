<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CustomerModel;

class CustomerController extends ResourceController
{
    use ResponseTrait;
    public function getAllCustomers()
    {
        $customer = new CustomerModel();
        $data = $customer->select('CONCAT(firstname, " ", lastname) as name, email, phone, id')->findAll();

        return $this->respond($data, 200);
    }

    public function addCustomer()
    {
        $json = $this->request->getJSON();

        $data = [
            'firstname' => $json->firstname,
            'lastname' => $json->lastname,
            'email' => $json->email,
            'phone' => $json->phone,
        ];

        // Check if the 'image' field exists in the uploaded files

        $customer = new CustomerModel();
        $response = $customer->save($data);
        return $this->respond($response, 200);

    }
}
