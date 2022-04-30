<?php

namespace App\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
class CreateExam
{
    private ExamDTO $examDto;
    public function __construct($examDto)
    {
        $this->examDto = $examDto;
    }

    public function execute()
    {

        DB::beginTransaction();
        try {
            $examId = DB::table('exams')->insertGetId([
                'name' => $this->examDto->getName(),
                'subject_id' => $this->examDto->getSubjectId(),
                'user_id' => $this->examDto->getUserId(),
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            ]);
            forEach($this->examDto->getQuestions() as $question) {
                $option1 = 0;
                $option2 = 0;
                $option3 = 0;
                $is_answer1= false;
                $is_answer2= false;
                $is_answer3= false;
                if (request()->hasFile('file')) {
                    $option1 = $question['option1'];
                    $option2 = $question['option2'];
                    $option3 = $question['option3'];
                    $is_answer1 = $question['option1'] === $question['answer'];
                    $is_answer2 = $question['option2'] === $question['answer'];
                    $is_answer3 = $question['option3'] === $question['answer'];
                } else {
                    $option1 = $question['options'][0]['option'];
                    $option2 = $question['options'][1]['option'];
                    $option3 = $question['options'][2]['option'];
                    $is_answer1 = $question['options'][0]['is_answer'];
                    $is_answer2 = $question['options'][1]['is_answer'];
                    $is_answer3 = $question['options'][2]['is_answer'];
                }
                $questionId = DB::table('questions')->insertGetId([
                    'number' => $question['number'],
                    'question' => $question['question'],
                    'level' => $question['level'],
                    'exam_id' => $examId,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' => $option1,
                    'is_answer' => $is_answer1,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' => $option2,
                    'is_answer' => $is_answer2,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' => $option3,
                    'is_answer' => $is_answer3,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
            }
            DB::commit();
            return ['success' => true, 'message' => 'success'];
        }catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage() . $e->getFile(), $e->getLine()];
        }
    }
}
