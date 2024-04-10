<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AccountModel;
use App\Models\CustomerModel;

class AccountController extends ResourceController
{

    use ResponseTrait;

    public function createAccount()
    {
        // Get the request data
        $requestData = $this->request->getJSON();

        // Create a new instance of the AccountModel
        $account = new AccountModel();
        $customer = new CustomerModel();

        // Prepare the data to be inserted
        $data = [
            'firstname' => $requestData->firstname,
            'lastname' => $requestData->lastname,
            'email' => $requestData->email,
            'phone' => $requestData->phone,
            'password' => password_hash($requestData->password, PASSWORD_DEFAULT), // Hash the password
        ];

        $data2 = [
            'firstname' => $requestData->firstname,
            'lastname' => $requestData->lastname,
            'email' => $requestData->email,
            'phone' => $requestData->phone,
            'role' => $requestData->role
        ];

        if ($requestData->role === 'customer') {
            $customerId = $customer->insert($data2);
            if ($customerId === false) {
                // Return an error response
                return $this->fail('Failed to create customer', 500);
            }
        }


        // Insert the data into the database
        $accountId = $account->insert($data);


        // Check if the insertion was successful
        if ($accountId === false) {
            // Return an error response
            return $this->fail('Failed to create account', 500);
        }

        // Return a success response
        return $this->respondCreated(['id' => $accountId], 'Account created');
    }

    public function login()
    {
        // Get the request data
        $requestData = $this->request->getJSON();

        // Retrieve the user from the database using the provided email or phone
        $accountModel = new AccountModel();
        $user = $accountModel
            ->where('email', $requestData->email)
            ->orWhere('phone', $requestData->email)
            ->first();

        // Verify if the user exists and the password is correct
        if ($user && password_verify($requestData->password, $user['password'])) {
            // Store the user's information in the session
            $session = session();
            $session->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'name' => $user['firstname'] . ' ' . $user['lastname'],
                'isLoggedIn' => true
            ]);

            // Redirect the user to a dashboard or home page
            return $this->respond(['message' => 'Login successful', 'session' => $session->get()]);
        }

        // Return an error message if the credentials are invalid
        return $this->failUnauthorized('Invalid email or password');
    }

    public function logout()
    {
        // Destroy the user's session
        $session = session();
        $session->destroy();

        // Return a success response
        return $this->respond(['message' => 'Logged out successfully']);
    }

    public function getSessionData()
    {
        $session = session();
        return $session->get(); // Returns an array of session data
    }


}
