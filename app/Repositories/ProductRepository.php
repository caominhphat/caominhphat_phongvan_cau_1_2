<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository extends ApiRepository
{

    protected function getModel()
    {
        return Product::class;
    }

    public function getRules()
    {
        return [
            'create' => [
                'name' => 'required|max:20',
                'store_id' => 'required|numeric|exists:stores,id',
                'price' => 'required|numeric|min:0',
            ],
            'edit' => [
                'id' => 'required|exists:products,id',
                'name' => 'max:20',
                'store_id' => 'numeric|exists:stores,id',
                'price' => 'min:0',
            ],
            'delete' => [
                'id' => 'required|exists:products,id',
            ]
        ];
    }

    public function getValidateMessages()
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Maximum name length is 20 characters',
            'store_id.required' => 'Store code is required',
            'store_id.numeric' => 'Store code must be numeric',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be numeric',
            'price.min' => 'Price must be greater than 0',
        ];
    }

    public function getList(Request $request)
    {
        $request = $request->only([
            'name',
            'price',
            'store_id'
        ]);
        $builder = $this->model;

        if ($request['name']) {
            $builder->where(DB::raw('LOWER(name)'), 'LIKE', '%'.strtolower($request['name']).'%');
        }

        if (data_get($request, 'price', null)) {
            $builder->where('price','LIKE','%'.$request['price'].'%');
        }

        if (data_get($request,'store_id',null)) {
            $builder->where('store_id', $request['store_id']);
        }

        return $builder->paginate($this->paginateLimit);
    }
}
