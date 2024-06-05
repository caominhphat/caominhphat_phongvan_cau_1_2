<?php

namespace App\Http\Controllers\Api;

use App\Repositories\StoreRepository;

class StoreController extends ApiController
{

    protected function getMainRepository()
    {
        return StoreRepository::class;
    }
}
