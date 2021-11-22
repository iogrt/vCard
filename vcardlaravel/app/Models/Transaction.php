<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'datetime',
        'type',
        'value',
        'old_balance',
        'new_balance',
        'payment_reference',
        'payment_type',
        'description',
        'custom_options',
        'custom_data',

    ];

    protected $nullable = [
        'category_id',
        'description',
        'custom_options',
        'custom_data'
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function paymentType(){
        return $this->belongsTo(PaymentType::class,'payment_type');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vCard(){
        return $this->belongsTo(Vcard::class,'vcard','phone_number');
    }

    public function pairVcard(){
        return $this->belongsTo(Vcard::class,'pair_vcard','phone_number');
    }

    public function pairTransaction(){
        return $this->hasOne(Transaction::class,'pair_transaction');
    }
}
