<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory; // utk bisa menggunakan factory
    protected $table = 'articles';
    protected $guarded = [];
}
