<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamStoreRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Http\Resources\ExamsCollection;
use App\Http\Resources\ExamWithoutAnswersResource;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use App\Service\CreateExam;
use App\Service\ExamDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{
    public function __construct() {
        $this->middleware('levelsAreOk')->only('update');
    }

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


    public function show($id): ExamResource
    {
        return new ExamResource(Exam::with('questions')
            ->with('options')
            ->where('id', $id)
            ->first()
        );
    }

    public function update(UpdateExamRequest $request, $id): JsonResponse
    {
        try{
            DB::beginTransaction();
                DB::table('exams')
                    ->where('id', $id)
                    ->update(
                        $request->only(['name','subject_id','user_id','low','medium','high'])
                    );
                Question::updateOrCreateByExamId($id);
                Option::updateOrCreateAllOptions(array_column($request['questions'],'options'));
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(),400);
        }
        return response()->json('Examen actualizado correctamente',200 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = Exam::find($id)
            ->delete();
        return response()->json(['success' => $result, 'message' => $result ? 'Examen eliminado' : 'No fue posible eliminar el examen']);
    }

    public function deleteQuestion(Request $request)
    {

        $question = Question::where('id',$request->questionID)->first();
        forEach($question->options as $option) {
            $option->delete();
        }
        $result = $question->delete();

        return response()->json(['success' => $result, 'message' => $result ? 'Pregunta eliminada' : 'No fue posible eliminar la pregunta examen']);
    }

    public function initExam(): JsonResponse
    {
        $response = [];
        $response['users'] = User::with('roles')->get();
        $response['subjects'] = Subject::all();

        return response()->json($response,200);
    }
    public function exam(Request $request, $id): ExamWithoutAnswersResource
    {
        return new ExamWithoutAnswersResource(Exam::with('questions')
            ->with('options')
            ->where('id', $id)
            ->first());
    }

    public function exams($user_id, $subject_id): JsonResponse
    {
        return response()->json(
          ['exams' => Exam::select('exams.id','exams.name','users.code')
              ->join('users','users.id', '=','exams.user_id')
              ->where('user_id', $user_id)
              ->where('subject_id', $subject_id)
              ->get()
          ],200
        );
    }
    public function appliedExams(){
        // TODO
    }
}
