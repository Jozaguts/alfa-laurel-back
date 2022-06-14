<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\SubjectStoreRequest;
use App\Models\Exam;
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

    public function store(SubjectStoreRequest $request)
    {
        $result = Subject::create($request->validated());
        return response()->json($result ? 'success': 'error', $result ? 201 : 400 );
    }

    public function destroy($id)
    {
        if (Exam::where('subject_id', $id)->count()){
            return abort(400,'No es posible eliminar la materia, debido a que cuenta con exámenes asignados');
        }
        $result = Subject::find($id)
            ->delete();

        return response()->json(['success' => $result, 'message' => $result ? 'Materia eliminada' : 'No fue posible eliminar la materia']);
    }
}
