<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'icon',
        'owner_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function userFavourite()
    {
        return $this->belongsToMany(User::class, 'favourite');
    }

    public function userRating()
    {
        return $this->belongsToMany(User::class, 'rating')->withPivot('rating');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
}
