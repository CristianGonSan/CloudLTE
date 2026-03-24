<?php

namespace App\Models;

use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Documents extends Authenticatable
{
    use HasFactory, TruncateText;

    protected $fillable = [

    ];

    protected $casts = [

    ];

    public function owner() {
        $this->belongsTo(User::class);
    }
}
