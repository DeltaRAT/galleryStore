<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        $this->belongsTo('user');
    }

    public function payment(){

        return $this->belongsTo('payment');
    }

    public function orderItems(){

        return $this->hasMany(OrderItem::class);
    }
}
