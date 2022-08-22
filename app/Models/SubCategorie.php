<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubCategorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_categorie';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id_sub_cat', 'name_sub_cat', 'categorie_id', 'created_at', 'updated_at', 'deleted_at', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return SubCategorie::select('sub_categorie.*', 'categorie.*', 'sub_categorie.id as id')
        ->join('categorie', 'sub_categorie.categorie_id', '=', 'categorie.id')

		->where('sub_categorie.name_sub_cat', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("sub_categorie.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return SubCategorie::select('sub_categorie.*', 'categorie.*', 'sub_categorie.id as id')
        ->join('categorie', 'sub_categorie.categorie_id', '=', 'categorie.id')

		->where('sub_categorie.name_sub_cat', 'like', $search)

        ->count();
    }
}
