<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Shetabit\Visitor\Traits\Visitable;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, 
        HasFactory, 
        Notifiable, 
        HasRoles, 
        SoftDeletes, 
        \OwenIt\Auditing\Auditable,
        Visitable,
        Visitor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'date_of_birth',
        'organization_id',
        'is_actived',
        'username_lpse',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function lpse()
    {
        return $this->belongsTo(LpseAccount::class, 'username_lpse', 'username');
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddresses::class);
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'user_pic');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function quotation()
    {
        return $this->hasMany(Quotation::class);
    }

    public function order()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function messages()
    {
        return $this->hasMany(ChMessage::class,'to_id');
    }

    public function getUnSeenMessagesAttribute()
    {
        $user = $this->where('id', $this->id)->withCount(['messages' => function($q) {
                    $q->where('seen', 0);
                }])->first();
        return $user->messages_count;
    }
}
