<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ApiController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->setMainRepository();
    }

    protected function setMainRepository()
    {
        $this->repository = app()->make(
            $this->getMainRepository()
        );
    }

    protected abstract function getMainRepository();

    public function create(Request $request)
    {
        try {
            $this->repository->create($request->toArray());
            return response('Create successfully', Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response($ex->getMessage(), $this->generateCode($ex->getCode()));
        }
    }

    public function update(Request $request)
    {
        try {
            $this->repository->update($request->toArray());
            return response('Update successfully', 200);
        } catch (\Exception $ex) {
            return response($ex->getMessage(), $this->generateCode($ex->getCode()));
        }
    }

    public function detail($id)
    {
        try {
            $data = $this->repository->find($id);
            return response($data, 200);
        } catch (\Exception $ex) {
            return response($ex->getMessage(), $this->generateCode($ex->getCode()));
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            return response('Delete successfully', 200);
        } catch (\Exception $ex) {
            return response($ex->getMessage(), $this->generateCode($ex->getCode()));
        }
    }

    public function index(Request $request)
    {
        try {
            $data = $this->repository->getList($request);
            return response($data, 200);
        } catch (\Exception $ex) {
            return response($ex->getMessage(), $this->generateCode($ex->getCode()));
        }
    }

    protected function generateCode($code)
    {
        if (!is_integer($code) || $code == 0) {
            $code = 500;
        }
        return $code;
    }
}
