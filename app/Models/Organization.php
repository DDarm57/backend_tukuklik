<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Organization extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'org_name',
        'org_type',
        'parent_org_id'
    ];

    protected $dates = ['deleted_at'];

    public function parent()
    {
        return $this->hasOne(Organization::class,'id', 'parent_org_id');
    }
}
