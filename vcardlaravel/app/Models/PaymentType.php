<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payment_types';
    protected $primaryKey = 'code';
    public $incrementing = 'false';
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'description',
        'validation_rules',
        'custom_options',
        'custom_data'
    ];

    protected $nullable = [
        'custom_options',
        'custom_data'
    ];

    protected $casts = [
        'validator_rules' => 'array',
    ];
}
