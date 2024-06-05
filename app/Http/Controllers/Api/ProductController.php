<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ProductRepository;

class ProductController extends ApiController
{

    protected function getMainRepository()
    {
        return ProductRepository::class;
    }
}
