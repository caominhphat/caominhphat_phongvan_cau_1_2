<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

abstract class ApiRepository
{
    protected $paginateLimit = 10;
    protected $model;
    protected $rules;
    protected $validateMessages;

    public function __construct()
    {
        $this->setModel();
        $this->rules = $this->getRules();
        $this->validateMessages = $this->getValidateMessages();
    }

    protected function setModel () {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    protected abstract function getModel();

    /**
     * get validate rules
     * @return array
     */
    public function getRules()
    {
        return [];
    }

    /**
     * get validate messages
     * @return array
     */
    public function getValidateMessages()
    {
        return [];
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $createRules = data_get($this->rules, 'create', null);

        if (!empty($this->rules) && $createRules) {

            $validate = Validator::make($attributes, $createRules, $this->getValidateMessages());

            if ($validate->fails()) {
                throw new \Exception($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return $this->model->create($attributes);
    }

    public function update($attributes)
    {
        $updateRules = data_get($this->rules, 'edit', null);

        if (!empty($this->rules) && $updateRules) {

            $validate = Validator::make($attributes, $updateRules, $this->getValidateMessages());

            if ($validate->fails() || !array_key_exists('id', $attributes)) {
                throw new \Exception($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return $this->model->where('id', $attributes['id'])->update($attributes);
    }

    public function delete($id)
    {
        $attributes = ['id' => $id];
        $deleteRules = data_get($this->rules, 'delete', null);
        if (!empty($this->rules) && $deleteRules) {

            $validate = Validator::make($attributes, $deleteRules, $this->getValidateMessages());

            if ($validate->fails()) {
                throw new \Exception($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $this->model->where('id', $id)->delete();
    }

    public abstract function getList(Request $request);
}
