<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use  App\Http\Requests\UserPostRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->get('role') === 'teachers') {
            return response()->json(User::whereHas('roles', function($query){
                $query->where('name', '=', 'maestro');
            })
                ->with('roles')
                ->get());
        } else {
            return response()->json(User::with('roles')->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPostRequest $request)
    {
        $data =  $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole($request->role);
        return response()->json($user ? 'success': 'error', $user ? 201 : 400 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return response()->json(User::with('roles')->where('id', $id)->first());
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();
        $validator = Validator::make($data['data'], [
            'name' => 'string',
            'code' =>'string|min:4',
            'email' => 'unique:users,email,'.$id,
            'password' => 'string|min:6|nullable',
            'paternal_name' => 'string',
            'maternal_name' => 'string',
            'birthday' => 'date',
            'address' => 'string',
            'phone' => 'digits:10,13',
            'contact_name' => 'string',
            'comments' => 'string|nullable',
            'role' => 'string|nullable',
        ]);


        $user = User::find($id);
        $user->name = $data['data']['name'];
        $user->code = $data['data']['code'];
        $user->email = $data['data']['email'];
        $user->paternal_name = $data['data']['paternal_name'];
        $user->maternal_name = $data['data']['maternal_name'];
        $user->birthday = $data['data']['birthday'];
        $user->address = $data['data']['address'];
        $user->phone = $data['data']['phone'];
        $user->contact_name = $data['data']['contact_name'];
        $user->comments = $data['data']['comments'];
        if (isset($data['data']['password']) && !is_null($data['data']['password'])) {
            $user->password  = Hash::make($data['data']['password']);
        }
        $result = $user->save();
        if (isset($data['data']['role']) && !is_null($data['data']['role'])) {
            $user->syncRoles([$data['data']['role']]);
        }
        return response()->json($result ? 'success': 'error', $result ? 200 : 400 );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = User::find($id)
            ->delete();

        return response()->json(['success' => $result, 'message' => $result ? 'Usuario eliminado' : 'No fue posible eliminar al usuario']);
    }
}
