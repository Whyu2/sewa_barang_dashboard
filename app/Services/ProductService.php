<?php


namespace App\Services;

use App\Repositories\Interface\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $repo
    )
    {
    }

    public function createProduct(array $data)
    {
        if (empty($data['name'])) {
            throw new \Exception("Product name is required");
        }

        return $this->repo->create($data);
    }

    public function getAllProducts()
    {
        return $this->repo->all();
    }

    public function getPaginatedProducts( $limit)
    {
        return $this->repo->paginate( $limit);
    }
}
