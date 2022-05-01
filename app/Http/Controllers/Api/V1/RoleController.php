<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return response()->json(Role::with('permissions')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        $role = Role::create(['guard_name' => 'sanctum', 'name' => $request->name]);
        $role->syncPermissions($request->validated()['permissions']);
        return response()->json($role ? 'success': 'error', $role ? 201 : 400 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Role::with('permissions')->where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::find($id);
        $role->syncPermissions($request->validated()['permissions']);
        $role->update(['name' => $request->name]);
        return response()->json($role ? 'success': 'error', $role ? 201 : 400 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Role::find($id)
            ->delete();

        return response()->json(['success' => $result, 'message' => $result ? 'Rol eliminado' : 'No fue posible eliminar el rol']);
    }
}
