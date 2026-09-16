<?php
namespace App\Repositories\Interface;
interface TransactionLogRepositoryInterface
{
    public function all();
    public function paginate($limit=10, $search=null, $action=null, $from=null, $to=null);
    public function find($id);
}
