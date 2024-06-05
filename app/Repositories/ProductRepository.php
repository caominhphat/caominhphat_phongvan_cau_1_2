<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;


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
        $builder = $this->model->query();
        $request = $request->toArray();

        if (data_get($request, 'name', null)) {
            $builder->where('name', 'LIKE', '%'.strtolower($request['name']).'%');
        }

        if (data_get($request, 'price', null)) {
            $price = json_decode($request['price'], true);
            if ($price && array_key_exists('min', $price) && array_key_exists('max', $price)) {
                $min = $price['min'];
                $max = $price['max'];
                if ($min > $max) {
                    $min = $price['max'];
                    $max = $price['min'];
                }

                $builder->whereBetween('price',[$min, $max]);
            }
        }

        $builder->with('stores');

        return $builder->paginate($this->paginateLimit);
    }
}
