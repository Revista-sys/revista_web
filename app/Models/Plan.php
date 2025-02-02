<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 * Class Plan
 *
 * @property float $price
 * @property string $name
 * @property int $type
 */

class Plan extends Model
{
    protected $fillable = [
        'price',
        'name',
        'type'
    ];
    protected $casts = [
        'price' => 'float',
        'name' => 'string',
        'type' => 'integer'
        
    ];

  
}
