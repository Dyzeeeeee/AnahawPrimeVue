<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MenuModel;
use App\Models\CategoryModel;

class MenuController extends ResourceController
{

    use ResponseTrait;

    public function getAllMenuItems()
    {
        $menu = new MenuModel();
        $data = $menu->select('menu.*, IFNULL(categories.name, "None") as category_name')
            ->join('categories', 'categories.id = menu.category_id', 'left')
            ->findAll();

        return $this->respond($data, 200);
    }




    public function addMenuItem()
    {
        $json = $this->request->getJSON();

        $data = [
            'name' => $json->name,
            'description' => $json->description,
            'price' => $json->price,
            'category_id' => $json->category_id,
        ];

        // Check if the 'image' field exists in the uploaded files

        $menu = new menuModel();
        $response = $menu->save($data);
        return $this->respond($response, 200);

    }
    public function archiveMenuItem($menuItemId)
    {
        $menu = new MenuModel();
        $menuItem = $menu->find($menuItemId);

        if (!$menuItem) {
            return $this->failNotFound('Menu item not found');
        }

        // Archive the menu item (e.g., set archived_at field to the current date/time)

        $data = [
            'archived_at' => date('Y-m-d H:i:s')
        ];

        $response = $menu->update($menuItemId, $data);

        if ($response) {
            return $this->respond(['message' => 'Menu item archived successfully'], 200);
        } else {
            return $this->failServerError('Failed to archive menu item');
        }
    }


    public function unarchiveMenuItem($menuItemId)
    {
        $menu = new MenuModel();
        $menuItem = $menu->find($menuItemId);

        if (!$menuItem) {
            return $this->failNotFound('Menu item not found');
        }

        // Archive the menu item (e.g., set archived_at field to the current date/time)

        $data = [
            'archived_at' => null
        ];

        $response = $menu->update($menuItemId, $data);

        if ($response) {
            return $this->respond(['message' => 'Menu item archived successfully'], 200);
        } else {
            return $this->failServerError('Failed to archive menu item');
        }
    }


}
