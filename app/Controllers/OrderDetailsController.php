<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OrderModel;
use App\Models\FoodStockModel;
use App\Models\RecipeModel;
use App\Models\OrderDetailsModel;

class OrderDetailsController extends ResourceController
{
    use ResponseTrait;

    public function addMenuItemToOrder()
    {
        $json = $this->request->getJSON();
        $orderDetails = new OrderDetailsModel();
        $foodStockModel = new FoodStockModel();
        $recipeModel = new RecipeModel();

        // Fetch existing details to compare changes
        $existingOrderDetails = $orderDetails
            ->where('menu_item_id', $json->menu_item_id)
            ->where('order_id', $json->order_id)
            ->first();

        if ($json->quantity === 0) {
            // Delete the record if the quantity is 0
            if ($existingOrderDetails) {
                $this->replenishStocks($existingOrderDetails['menu_item_id'], $existingOrderDetails['quantity']);
                $orderDetails->delete($existingOrderDetails['id']);
            }
        } else {
            // Adjust the stock based on the difference in quantity
            $difference = $existingOrderDetails ? $json->quantity - $existingOrderDetails['quantity'] : $json->quantity;
            if ($difference < 0) {
                $this->replenishStocks($json->menu_item_id, abs($difference));
            } elseif ($difference > 0) {
                $this->consumeStocks($json->menu_item_id, $difference);
            }

            // Update or insert order details
            if ($existingOrderDetails) {
                $orderDetails->update($existingOrderDetails['id'], $json);
            } else {
                $orderDetails->insert($json);
            }
        }

        // Update order's total price
        $this->updateOrderTotal($json->order_id);

        return $this->respond(['success' => true, 'message' => 'Order detail updated/added/deleted successfully'], 200);
    }

    private function replenishStocks($menuItemId, $quantity)
    {
        $foodStockModel = new FoodStockModel();
        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->where('menu_id', $menuItemId)->findAll();

        foreach ($recipes as $recipe) {
            $foodStock = $foodStockModel->find($recipe['foodstock_id']);
            if ($foodStock) {
                $newQuantity = $foodStock['quantity'] + ($recipe['quantity'] * $quantity);
                $foodStockModel->update($recipe['foodstock_id'], ['quantity' => $newQuantity]);
            }
        }
    }

    private function consumeStocks($menuItemId, $quantity)
    {
        $foodStockModel = new FoodStockModel();
        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->where('menu_id', $menuItemId)->findAll();

        foreach ($recipes as $recipe) {
            $foodStock = $foodStockModel->find($recipe['foodstock_id']);
            if ($foodStock) {
                $newQuantity = $foodStock['quantity'] - ($recipe['quantity'] * $quantity);
                $foodStockModel->update($recipe['foodstock_id'], ['quantity' => $newQuantity]);
            }
        }
    }

    private function updateOrderTotal($orderId)
    {
        $orderDetailsModel = new OrderDetailsModel();
        $orderModel = new OrderModel();
        $totalPrice = $orderDetailsModel->selectSum('subtotal')->where('order_id', $orderId)->first()['subtotal'];
        $orderModel->update($orderId, ['total_price' => $totalPrice]);
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
