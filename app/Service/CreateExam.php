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
                'low' => $this->examDto->getCounterLow(),
                'medium' => $this->examDto->getCounterMedium(),
                'high' => $this->examDto->getCounterHigh(),
                "created_at" =>  \Carbon\Carbon::now(),
            ]);

            forEach($this->examDto->getQuestions() as $question) {

                $questionId = DB::table('questions')->insertGetId([
                    'number' => $question['number'],
                    'question' => $question['question'],
                    'answer' => $question['answer'],
                    'level' => $question['level'],
                    'exam_id' => $examId,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' => $question['options'][0]['option'],
                    'number' => $question['options'][0]['number'],
                    'is_answer' => $question['options'][0]['is_answer'],
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' =>$question['options'][1]['option'],
                    'number' => $question['options'][1]['number'],
                    'is_answer' => $question['options'][1]['is_answer'],
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                DB::table('options')->insert([
                    'question_id' => $questionId,
                    'option' => $question['options'][2]['option'],
                    'number' => $question['options'][2]['number'],
                    'is_answer' => $question['options'][2]['is_answer'],
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
