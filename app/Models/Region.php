<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'regions';
    protected $fillable = ['name', 'description'];


    public function rentTransaction()
    {
        return $this->hasMany(RentTransaction::class);
    }

}
