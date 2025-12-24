<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentTransaction extends Model
{
    use HasFactory;

    protected $table = 'rent_transactions';

    protected $fillable = [
        'product_id',
        'region_id',
        'renter_name',
        'renter_phone',
        'rent_date',
        'qty',
        'expected_return_date',
        'rent_price',
        'return_date',
        'status',
        'notes',
        'pickup_proof_url',
        'return_proof_url',
    ];

    protected $casts = [
        'rent_date' => 'datetime',
        'expected_return_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
