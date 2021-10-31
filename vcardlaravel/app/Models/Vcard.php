<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vcard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vcards';
    protected $primaryKey = 'phone_number';
    public $incrementing = 'false';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'photo_url',
        'password',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit',
        'custom_options',
        'custom_data',
    ];

    protected $hidden = [
        'password',
        'confirmation_code'
    ];

    protected $casts = [
        'blocked' => 'bool',
    ];

    public function categories(){
        return $this->hasMany(Category::class,'vcard');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class,'vcard');
    }

}
