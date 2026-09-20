<?php

namespace App\Repositories;

use App\Repositories\Interface\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('region')->orderBy('id', 'ASC')->get();
    }

    public function paginate($limit = 10)
    {
        return $this->model->with('region')->orderBy('id', 'ASC')->paginate($limit);
    }

    public function find($id)
    {
        return $this->model->with('region')->find($id);
    }
}
