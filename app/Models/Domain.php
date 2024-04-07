<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function approvedAds()
    {
        return $this->hasMany(Ad::class)->where('status',2);
    }
}
