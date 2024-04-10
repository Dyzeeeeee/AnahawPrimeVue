<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SessionModel;

class SessionController extends ResourceController
{

    use ResponseTrait;

    public function getAllSessions()
    {
        $session = new SessionModel();
        $data = $session->select('sessions.*, accounts.firstname, accounts.lastname')
            ->join('accounts', 'accounts.id = sessions.cashier_id')
            ->findAll();

        return $this->respond($data, 200);
    }

    public function addSession()
    {
        $json = $this->request->getJSON();

        $data = [
            'opening_cash' => $json->opening_cash,
            'note' => $json->note,
            'cashier_id' => $json->cashier_id,
        ];

        // Check if the 'image' field exists in the uploaded files

        $session = new SessionModel();
        $response = $session->save($data);
        return $this->respond($response, 200);
    }


}
