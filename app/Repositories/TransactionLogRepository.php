<?php
namespace App\Repositories;
use App\Models\TransactionLog;
use App\Repositories\Interface\TransactionLogRepositoryInterface;
class TransactionLogRepository extends BaseRepository implements TransactionLogRepositoryInterface
{
    public function __construct(TransactionLog $model){ $this->model=$model; }
    public function paginate($limit=10, $search=null, $action=null, $from=null, $to=null)
    {
        $q = $this->model->with(['transaction.product','product','user'])->orderBy('created_at','desc');
        if ($search) $q->where(function($w) use($search){ $w->where('action','ilike',"%$search%")->orWhereHas('product', fn($p)=>$p->where('name','ilike',"%$search%"))->orWhereHas('user', fn($u)=>$u->where('name','ilike',"%$search%")); });
        if ($action && $action!=='all') $q->where('action',$action);
        if ($from) $q->whereDate('created_at','>=',$from);
        if ($to) $q->whereDate('created_at','<=',$to);
        return $q->paginate($limit);
    }
    public function all(){ return $this->model->with(['transaction.product','product','user'])->orderBy('created_at','desc')->get(); }
    public function find($id){ return $this->model->with(['transaction.product','product','user'])->find($id); }
}
