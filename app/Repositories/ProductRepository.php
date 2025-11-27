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
}
