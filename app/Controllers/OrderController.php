<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OrderModel;
use App\Models\OrderDetailsModel;
use App\Models\CustomerModel;

class OrderController extends ResourceController
{
    use ResponseTrait;


    public function getAllOrders()
    {
        $order = new OrderModel();
        $orders = $order->select('orders.*, IFNULL(CONCAT(customers.firstname, " ", customers.lastname), "N/A") as customer_name, IFNULL(CONCAT(accounts.firstname, " ", accounts.lastname), "N/A") as cashier_name')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->join('sessions', 'sessions.id = orders.session_id', 'left')
            ->join('accounts', 'accounts.id = sessions.cashier_id', 'left') // Joining the accounts table
            // ->where('session_id', $sessionId)
            ->orderBy('id', 'DESC')
            ->findAll();


        $orderDetailsModel = new OrderDetailsModel();

        // Add order details to each order
        foreach ($orders as &$order) {
            $order['details'] = $orderDetailsModel
                ->select('order_details.*, menu.name as menu_item_name')
                ->join('menu', 'menu.id = order_details.menu_item_id', 'left')
                ->where('order_id', $order['id'])
                ->findAll();
        }

        if ($orders) {
            $response = [
                'success' => true,
                'orders' => $orders
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No orders found for the session'
            ];
            return $this->respond($response, 404);
        }
    }



    public function createNewOrder()
    {
        $json = $this->request->getJSON();

        $data = [
            'session_id' => $json->session_id,
        ];

        $order = new OrderModel();
        $orderId = $order->insert($data); // Use insert instead of save

        if ($orderId) {
            $response = [
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $orderId
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to create order'
            ];
            return $this->respond($response, 500); // Use a suitable HTTP status code for failure
        }
    }

    public function addCustomerToOrder($orderId)
    {
        $json = $this->request->getJSON();

        $data = [
            'customer_id' => $json->customer_id,
        ];

        $order = new OrderModel();
        $updated = $order->update($orderId, $data);

        if ($updated) {
            $response = [
                'success' => true,
                'message' => 'Customer added to order successfully'
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to add customer to order'
            ];
            return $this->respond($response, 500); // Use a suitable HTTP status code for failure
        }
    }

    public function changeService($orderId)
    {
        $json = $this->request->getJSON();

        $data = [
            'service' => $json->service,
        ];

        $order = new OrderModel();
        $updated = $order->update($orderId, $data);

        if ($updated) {
            $response = [
                'success' => true,
                'message' => 'service changed successfully'
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to add customer to order'
            ];
            return $this->respond($response, 500); // Use a suitable HTTP status code for failure
        }
    }


    public function getOrderDetails($orderId)
    {
        $order = new OrderModel();
        $orderDetails = $order->select('orders.*, IFNULL(CONCAT(customers.firstname, " ", customers.lastname), "N/A") as customer_name, customers.phone as customer_phone, customers.email as customer_email')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->find($orderId);

        if ($orderDetails) {
            $response = [
                'order_id' => $orderDetails['id'],
                'session_id' => $orderDetails['session_id'],
                'customer_id' => $orderDetails['customer_id'],
                'customer_name' => $orderDetails['customer_name'],
                'customer_phone' => $orderDetails['customer_phone'], // Added customer phone
                'customer_email' => $orderDetails['customer_email'], // Added customer email
                'total_price' => $orderDetails['total_price'],
                'service' => $orderDetails['service'],
            ];

            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Order not found'
            ];
            return $this->fail($response, 404);
        }
    }

    public function getAllSessionOrders($sessionId)
    {
        $order = new OrderModel();
        $orders = $order->select('orders.*, IFNULL(CONCAT(customers.firstname, " ", customers.lastname), "N/A") as customer_name')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->where('session_id', $sessionId)
            ->orderBy('id', 'DESC')
            ->findAll();

        $orderDetailsModel = new OrderDetailsModel();

        // Add order details to each order
        foreach ($orders as &$order) {
            $order['details'] = $orderDetailsModel
                ->select('order_details.*, menu.name as menu_item_name')
                ->join('menu', 'menu.id = order_details.menu_item_id', 'left')
                ->where('order_id', $order['id'])
                ->findAll();
        }

        if ($orders) {
            $response = [
                'success' => true,
                'orders' => $orders
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No orders found for the session'
            ];
            return $this->respond($response, 404);
        }
    }


    public function addCustomerPayment($orderId)
    {
        $json = $this->request->getJSON();

        // Retrieve the order details to get the total_price
        $orderModel = new OrderModel();
        $orderDetails = $orderModel->find($orderId);

        // Calculate the change1 value
        $change1 = $orderDetails['total_price'] - $json->tendered;

        // Update data with the change1 value
        $data = [
            'tendered' => $json->tendered,
            'status' => $json->status,
            'change1' => $change1,
        ];

        $updated = $orderModel->update($orderId, $data);

        if ($updated) {
            $response = [
                'success' => true,
                'message' => 'Payment added successfully'
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to add customer payment to order'
            ];
            return $this->respond($response, 500); // Use a suitable HTTP status code for failure
        }
    }
}
