<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($supplier) {
            $supplier->address()->delete();
        });
    }


    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

}

