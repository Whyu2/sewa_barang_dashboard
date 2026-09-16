<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\RentTransaction;
use App\Models\TransactionLog;
class TransactionLogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RentTransaction::all() as $tx) {
            TransactionLog::create([
                'transaction_id' => $tx->id,
                'product_id' => $tx->product_id,
                'user_id' => 1,
                'action' => $tx->status,
                'from_status' => null,
                'to_status' => $tx->status,
                'metadata' => ['qty'=>$tx->qty,'rent_price'=>$tx->rent_price,'renter'=>$tx->renter_name],
                'created_at' => $tx->created_at,
                'updated_at' => $tx->created_at,
            ]);
        }
    }
}
