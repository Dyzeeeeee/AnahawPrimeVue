<?php

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FoodStockModel;
use App\Models\CategoryModel;

class FoodStockController extends ResourceController
{
    use ResponseTrait;

    public function getAllFoodStocks()
    {
        $foodStocks = new FoodStockModel();
        $data = $foodStocks->findAll();

        return $this->respond($data, 200);
    }
}
