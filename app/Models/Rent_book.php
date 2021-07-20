<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent_book extends Model
{
    use HasFactory;
    public $table="rent_book";

    protected $fillable = [
        'book_id',
        'user_id',
    ];
}
