<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // memastikan PostgreSQL tidak salah referensi

    protected $fillable = [
        'name',
        'category_id',
        'qr_code',
        'photo_url',
        'status',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A product belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A product belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // A product has many rent transactions
    public function rentTransactions()
    {
        return $this->hasMany(RentTransaction::class);
    }

    // A product has many logs
    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (opsional)
    |--------------------------------------------------------------------------
    */

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isRented()
    {
        return $this->status === 'rented';
    }
}
