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
        'qr_uuid',
        'qr_code_url',
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

    public function productRegion()
    {
        return $this->hasMany(ProductRegion::class);
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

    protected $appends = ['category_name'];

    public function getCategoryNameAttribute()
    {
        return $this->category?->name;
    }

    public function getProductRegion($query)
    {
        return $this->productRegion;
    }

 
    protected $hidden = ['category'];
}
