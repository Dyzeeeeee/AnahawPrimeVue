<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CategoryModel;

class CategoryController extends ResourceController
{
    use ResponseTrait;

    public function getAllCategories()
    {
        $category = new CategoryModel();
        $data = $category->findAll();

        return $this->respond($data, 200);
    }

    public function addCategory()
    {
        $json = $this->request->getJSON();

        $data = [
            'name' => $json->name,
        ];

        // Check if the 'image' field exists in the uploaded files

        $category = new CategoryModel();
        $response = $category->save($data);
        return $this->respond($response, 200);

    }
}
