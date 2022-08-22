<?php

namespace App\Models;

use Encrypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CustomRole extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public $incrementing = true;

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public $timestamps = false;

    public static function pagination($search = "", $sortBy, $sort, $skip, $itemsPerPage)
    {
        $roles = CustomRole::where('name', 'like', $search)
        ->orderBy($sortBy, $sort)
        ->get();

        foreach ($roles as $key => $role) {
            $role->permissions = Role::findById($role->id)->getAllPermissions();

            $role->permissions = Encrypt::encryptObject($role->permissions, "id");
        }

        return $roles;
    }

    public static function counter($search = "")
    {
        return CustomRole::where('name', 'like', $search)
        ->count();
    }
}
