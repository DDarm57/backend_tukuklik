<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpseAccount extends Model
{
    use HasFactory;

    protected $table = "lpse_account";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
