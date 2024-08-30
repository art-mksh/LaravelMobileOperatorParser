<?php

namespace ArtMksh\PhoneOperatorInfo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneOperatorInfo extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'operator', 'region'];
}