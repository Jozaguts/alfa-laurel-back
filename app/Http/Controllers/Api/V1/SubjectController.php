<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required']);
        $result = Subject::create($validator->validated());
        return response()->json($result ? 'success': 'error', $result ? 201 : 400 );
    }

    public function destroy($id)
    {
        $result = Subject::find($id)
            ->delete();

        return response()->json(['success' => $result, 'message' => $result ? 'Materia eliminada' : 'No fue posible eliminar la materia']);
    }
}
