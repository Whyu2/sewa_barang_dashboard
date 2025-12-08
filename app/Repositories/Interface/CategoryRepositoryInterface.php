<?php
namespace App\Repositories\Interface;

interface CategoryRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function paginate($limit = 10);
    public function destroy($id);
}
