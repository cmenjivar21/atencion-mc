<?php

namespace App\Http\Controllers;

use App\Models\CustomRole;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Encrypt;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $roles = Role::all();
        // $roles = Encrypt::encryptObject($roles, "id");

        // return response()->json([
        //     "status"=>"success",
        //     "message"=>"Registro creado correctamente.",
        //     "roles" => $roles
        // ]);
        $itemsPerPage = $request->itemsPerPage;
        $skip = $request->page * $request->itemsPerPage;

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = '%' . $request->search . '%';

        $roles = CustomRole::pagination($search, $sortBy, $sort, $skip, $itemsPerPage);
        $roles = Encrypt::encryptObject($roles, "id");

        $total = CustomRole::counter($search);

        return response()->json([
            "status" => "success",
            "message" => "Roles obtenidos correctamente.",
            "roles" => $roles,
            "total" => $total,
        ]);
    }

    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        foreach ($request->permissions as $key => $permission) {
            $role->givePermissionTo($permission['name']);
        }

        return response()->json([
            "status" => "success",
            "message" => "Rol actualizado correctamente.",
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->permissions);
        $id = (int)Encrypt::decryptValue($request->id);
        $role = Role::findById($id);
        $role->name = $request->name;
        $role->save();

        $permissions = [];
        foreach ($request->permissions as $key => $permission) {
            array_push($permissions, $permission['name']);
        }

        $role->syncPermissions($permissions);

        return response()->json([
            "status" => "success",
            "message" => "Rol actualizado correctamente.",
        ]);
    }

    /**
     * Validate if the user has access to the resource.
     *
     * @return bool
     */
    public static function verifyPermission($permission = "")
    {
        if (auth()->user()->hasPermissionTo($permission)) {
            return [
                "success" => true,
                "status" => 200,
                "response" => "success",
                "message" => "Usuario con permiso para realizar esta acción.",
            ];
        }

        return [
            "success" => false,
            "status" => 200,
            "response" => "error",
            "message" => "No posees los permisos necesarios para realizar esta acción.",
        ];
    }

    public static function getAllowedRoles()
    {
        $roles = auth()->user()->getRoleNames();

        return $roles[0];
    }
}
