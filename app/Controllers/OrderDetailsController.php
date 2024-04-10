<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OrderModel;
use App\Models\OrderDetailsModel;

class OrderDetailsController extends ResourceController
{
    use ResponseTrait;

    public function addMenuItemToOrder()
    {
        $json = $this->request->getJSON();

        $orderDetailsData = [
            'quantity' => $json->quantity,
            'menu_item_id' => $json->menu_item_id,
            'order_id' => $json->order_id,
            'subtotal' => $json->subtotal,
        ];

        $orderDetails = new OrderDetailsModel();

        if ($json->quantity === 0) {
            // Delete the record if the quantity is 0
            $existingOrderDetails = $orderDetails
                ->where('menu_item_id', $json->menu_item_id)
                ->where('order_id', $json->order_id)
                ->delete();
        } else {
            // Check if a record with the same menu_item_id and order_id already exists
            $existingOrderDetails = $orderDetails
                ->where('menu_item_id', $json->menu_item_id)
                ->where('order_id', $json->order_id)
                ->first();

            if ($existingOrderDetails) {
                // Update the existing record
                $orderDetails->update($existingOrderDetails['id'], $orderDetailsData);
            } else {
                // Insert a new record
                $orderDetails->insert($orderDetailsData);
            }
        }

        // Update the total_price in the OrderModel
        $orderModel = new OrderModel();
        $totalPrice = $orderDetails
            ->selectSum('subtotal')
            ->where('order_id', $json->order_id)
            ->first()['subtotal'];

        $orderModel->update($json->order_id, ['total_price' => $totalPrice]);

        $response = [
            'success' => true,
            'message' => 'Order detail updated/added/deleted successfully'
        ];
        return $this->respond($response, 200);
    }



    public function getOrderItems($orderId)
    {
        $orderDetailsModel = new OrderDetailsModel();

        // Fetch all order items associated with the given order ID, joining the menu table
        $orderItems = $orderDetailsModel
            ->select('order_details.order_id, order_details.menu_item_id, menu.name as menu_item_name, menu.price as menu_item_price, order_details.quantity, order_details.subtotal')
            ->join('menu', 'menu.id = order_details.menu_item_id', 'left')
            ->where('order_id', $orderId)
            ->findAll();

        if ($orderItems) {
            // Construct response with all order items
            $response = [];
            foreach ($orderItems as $orderItem) {
                $response[] = [
                    'order_id' => $orderItem['order_id'],
                    'menu_item_id' => $orderItem['menu_item_id'],
                    'menu_item_name' => $orderItem['menu_item_name'],
                    'menu_item_price' => $orderItem['menu_item_price'],
                    'quantity' => $orderItem['quantity'],
                    'subtotal' => $orderItem['subtotal'],
                ];
            }

            return $this->respond($response, 200);
        } else {
            // Return an empty array if no order items are found
            return $this->respond([], 200);
        }
    }








}
