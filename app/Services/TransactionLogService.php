<?php
namespace App\Services;
use App\Repositories\Interface\TransactionLogRepositoryInterface;
class TransactionLogService
{
    public function __construct(protected TransactionLogRepositoryInterface $repo){}
    public function paginate($limit=10,$search=null,$action=null,$from=null,$to=null){ return $this->repo->paginate($limit,$search,$action,$from,$to); }
    public function all(){ return $this->repo->all(); }
}
