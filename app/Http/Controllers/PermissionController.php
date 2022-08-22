<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Encrypt;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::all();

        $permissions = Encrypt::encryptObject($permissions, "id");

        return response()->json([
            "status" => "success",
            "message" => "Permisos obtenidos correctamente.",
            "records" => $permissions,
            "success" => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  permissions
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permissions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    }
}
