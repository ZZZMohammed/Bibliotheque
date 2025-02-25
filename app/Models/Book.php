<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Book extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['designation', 'description', 'prix', 'auteur', 'cover'];

    protected $dates =['deleted_at'] ;
}
