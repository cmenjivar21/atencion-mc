<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorie';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'name_cat', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return Categorie::select('categorie.*', 'categorie.id as id')
        
		->where('categorie.name_cat', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("categorie.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return Categorie::select('categorie.*', 'categorie.id as id')
        
		->where('categorie.name_cat', 'like', $search)

        ->count();
    }
}
