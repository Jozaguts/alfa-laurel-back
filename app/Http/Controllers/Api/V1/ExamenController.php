<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamStoreRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
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


    public function show($id): ExamResource
    {
        return new ExamResource(Exam::with('questions')
            ->with('options')
            ->where('id', $id)
            ->first()
        );
    }

    public function update(Request $request, $id)
    {

        $exam = Exam::where('id', $id)->first();

        $exam->name = $request->name;
        $exam->subject_id = $request->subject_id;
        $exam->user_id = $request->user_id;
        $exam->save();
        $counterLow = 0;
        $counterMedium = 0;
        $counterHigh = 0;
        forEach($request['questions'] as $question ) {
            $q = Question::updateOrCreate(
                ['number' => $question['number'], 'exam_id' => $id],
                [
                    'question'=> $question['question'],
                    'answer'=> (int)$question['answer'],
                    'level'=> $question['level'],
                ]
            );
            switch ($question['level']) {
                case "A":
                    $counterHigh++;
                break;
                case "M":
                    $counterMedium++;
                break;
                case "B":
                    $counterLow++;
                break;
            }
            Option::updateOrCreate(
                ['number' => $question['options'][0]['number'], 'question_id' => $q->id],
                [
                    'is_answer' => $question['options'][0]['is_answer'],
                    'option'=> $question['options'][0]['option'],
                ]
            );

            Option::updateOrCreate(
                ['number' => $question['options'][1]['number'], 'question_id' => $q->id],
                [
                    'is_answer' => $question['options'][1]['is_answer'],
                    'option'=> $question['options'][1]['option'],
                ]
            );
            Option::updateOrCreate(
                ['number' => $question['options'][2]['number'], 'question_id' => $q->id],
                [
                    'is_answer' => $question['options'][2]['is_answer'],
                    'option'=> $question['options'][2]['option'],
                ]
            );
        }
        if ($counterLow < (int)$request->low) {
            abort('400',"El numero de preguntas en nivel BAJO ({$request->low}) que quieres asignar es mayor a la cantidad disponible ({$counterLow})");
        }
        if ($counterMedium < (int)$request->medium) {
            abort('400',"El numero de preguntas en nivel MEDIO ({$request->medium}) que quieres asignar es mayor a la cantidad disponible ({$counterMedium})");
        }
        if ($counterHigh < (int)$request->high) {
        abort('400',"El numero de preguntas en nivel ALTO  ({$request->high}) que quieres asignar es mayor a la cantidad disponible ({$counterHigh})");
        }
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
}
