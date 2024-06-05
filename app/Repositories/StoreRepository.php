<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreRepository extends ApiRepository
{
    protected function getModel()
    {
        return Store::class;
    }

    public function getRules()
    {
        return [
            'create' => [
                'name' => 'required|max:20',
                'user_id' => 'required|numeric|exists:users,id',
                'location' => 'required',
            ],
            'edit' => [
                'id' => 'required|exists:stores,id',
                'name' => 'max:20',
                'user_id' => 'numeric|exists:users,id',
            ],
            'delete' => [
                'id' => 'required|exists:stores,id',
            ]
        ];
    }

    public function getValidateMessages()
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Maximum name length is 20 characters',
            'user_id.required' => 'Store code is required',
            'user_id.numeric' => 'Store code must be numeric',
            'location.required' => 'Location is required',
            'id.required' => 'Id is required',
            'id.exists' => 'Id not exist in database',
        ];
    }

    public function getList(Request $request)
    {
        $builder = $this->model->query();
        $request = $request->toArray();

        if (data_get($request,'name',  null)) {
            $builder->where('name','LIKE','%'.$request['name'].'%');
        }

        if (data_get($request,'location',  null)) {
            $builder->where('location','LIKE','%'.$request['location'].'%');
        }

        $builder->with('products');
        $builder->with('users');

        return $builder->paginate($this->paginateLimit);
    }
}
