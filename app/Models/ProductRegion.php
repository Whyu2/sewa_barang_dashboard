<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductRegion extends Model
{
    use HasFactory;

    protected $table = 'product_regions';

    protected $fillable = [
        'product_id',
        'region_id',
        'qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function getRegionNameAttribute()
    {
        return $this->region?->name;
    }

    protected $appends = ['region_name'];

    protected $hidden = ['region', 'created_at', 'updated_at', 'id'];
 
}
