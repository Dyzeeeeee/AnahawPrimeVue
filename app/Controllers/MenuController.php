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
        helper('url');  // Load URL helper for base_url() function
        $menu = new MenuModel();
        $data = $menu->select('menu.*, IFNULL(categories.name, "None") as category_name')
            ->join('categories', 'categories.id = menu.category_id', 'left')
            ->findAll();

        // Define the base URL for the images
        $baseUrl = base_url('uploads/');  // Ensure this matches the directory structure in public

        // Append the full URL to each image filename
        foreach ($data as &$item) {
            if (!empty($item['image'])) {
                $item['image'] = $baseUrl . $item['image'];
            } else {
                $item['image'] = null;  // Or a default image URL if you prefer
            }
        }

        return $this->respond($data, 200);
    }





    public function addMenuItem()
    {
        $json = $this->request->getJSON();

        $data = [
            'name' => $json->name,
            'description' => $json->description,
            'price' => $json->price,
            'category_id' => $json->category_id
        ];

        if (!empty($json->image)) {
            // Decode the image
            $imageData = $json->image;
            list($type, $imageData) = explode(';', $imageData);
            list(, $imageData) = explode(',', $imageData);
            $imageData = base64_decode($imageData);

            // Determine file extension based on the MIME type
            $mimeType = explode(':', $type)[1];
            switch ($mimeType) {
                case 'image/jpeg':
                    $fileExt = 'jpg';
                    break;
                case 'image/png':
                    $fileExt = 'png';
                    break;
                case 'image/gif':
                    $fileExt = 'gif';
                    break;
                case 'image/avif':
                    $fileExt = 'avif';
                    break;
                default:
                    // Handle unknown or unsupported types
                    return $this->failValidationError('Unsupported image type.');
            }

            // Create a filename and save the file
            $filename = uniqid() . '.' . $fileExt;
            file_put_contents(FCPATH  . 'uploads/' . $filename, $imageData);
            $data['image'] = $filename;  // Store just the filename in the database
        }

        $menuModel = new MenuModel();
        if ($menuModel->insert($data)) {
            return $this->respondCreated($data, 'Menu item added successfully.');
        } else {
            return $this->failServerError('Failed to add menu item');
        }
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
