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
        $data = $customer->select('CONCAT(firstname, " ", lastname) as name, email, phone, id, archived_at')->findAll();

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

    public function archiveCustomer($customerId)
    {
        $customer = new CustomerModel();
        $customerData = $customer->find($customerId);

        if (!$customerData) {
            return $this->failNotFound('Menu item not found');
        }

        // Archive the menu item (e.g., set archived_at field to the current date/time)

        $data = [
            'archived_at' => date('Y-m-d H:i:s')
        ];

        $response = $customer->update($customerId, $data);

        if ($response) {
            return $this->respond(['message' => 'Menu item archived successfully'], 200);
        } else {
            return $this->failServerError('Failed to archive menu item');
        }
    }


    public function unarchiveCustomer($customerId)
    {
        $customer = new CustomerModel();
        $customerData = $customer->find($customerId);

        if (!$customerData) {
            return $this->failNotFound('Menu item not found');
        }

        // Archive the menu item (e.g., set archived_at field to the current date/time)

        $data = [
            'archived_at' => null
        ];

        $response = $customer->update($customerId, $data);

        if ($response) {
            return $this->respond(['message' => 'Menu item archived successfully'], 200);
        } else {
            return $this->failServerError('Failed to archive menu item');
        }
    }


    
}
