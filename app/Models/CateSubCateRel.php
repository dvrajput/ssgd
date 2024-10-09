<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateSubCateRel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_code',
        'sub_category_code',
    ];
}
