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
        return Product::with(['category', 'region'])->get();
    }

    public function paginate($limit = 10)
    {
        return Product::with(['category', 'region'])->paginate($limit);
    }

    public function find($id)
    {
        return Product::with(['category', 'region'])->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }
}
