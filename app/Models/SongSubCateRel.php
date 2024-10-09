<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongSubCateRel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'song_code',
        'sub_category_code',
    ];
}
