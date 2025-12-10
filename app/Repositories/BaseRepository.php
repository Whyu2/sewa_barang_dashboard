<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function all()
    {
        return $this->model->all();
    }

    public function paginate($limit = 10)
    {
        return $this->model->orderBy('id', 'ASC')->paginate($limit);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data)
    {
        return $model->update($data);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
