<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
protected $fillable = [
    'filename',
    'title',
    'file_path',
    'public_id',
    'size',
    'content',
    'category',
];}

