<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamStoreRequest;
use App\Models\Exam;
use App\Service\CreateExam;
use App\Service\ExamDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
       return response()->json(Exam::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamStoreRequest $request
     */
    public function store(ExamStoreRequest $request)
    {
        $exam =  new CreateExam(new ExamDTO($request->validated()));
        $result = $exam->execute();
        return response()->json($result['message'], $result['success'] ? 201 : 400 );
    }


    public function show($id)
    {
       return response()->json(
           Exam::find($id)->questions()->get()
       );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
