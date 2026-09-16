<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TransactionLog extends Model
{
    use HasFactory;
    protected $table = 'transaction_logs';
    protected $fillable = ['transaction_id','product_id','user_id','action','from_status','to_status','metadata'];
    protected $casts = ['metadata'=>'array','created_at'=>'datetime','updated_at'=>'datetime'];
    public function transaction(){ return $this->belongsTo(RentTransaction::class,'transaction_id'); }
    public function product(){ return $this->belongsTo(Product::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
