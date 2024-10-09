<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongCategoryRel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'song_code',
        'category_code',
    ];
}
