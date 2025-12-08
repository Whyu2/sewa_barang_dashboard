<?php
namespace App\Repositories;

use App\Repositories\Interface\ProductRepositoryInterface;
use App\Models\Product;
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['category', 'region'])->get();
    }

    public function paginate($limit = 10)
    {
        return $this->model->with(['category', 'region'])->paginate($limit);
    }

    public function find($id)
    {
        return $this->model->with(['category', 'region'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
