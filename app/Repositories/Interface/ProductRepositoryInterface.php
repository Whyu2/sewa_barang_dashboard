<?php
namespace App\Repositories\Interface;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function paginate($limit = 10);
}
