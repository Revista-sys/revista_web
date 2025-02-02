<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
// use App\PrimeSubscription;

use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class PrimeSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'customer_id' => 'integer',
        'customer_type'=>'string',
        'payment_status'=>'string',
        'transaction_ref'=>'string',
        'amount'=>'float',
        'duration'=>'integer'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function wishList(): hasMany
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function orders(): hasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address');
    }

    // Old Relation: compare_list
    public function compareList(): hasMany
    {
        return $this->hasMany(ProductCompare::class, 'user_id');
    }
}
