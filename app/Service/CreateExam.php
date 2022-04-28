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
        try {
            DB::beginTransaction();
               $examId = DB::table('exams')->insertGetId([
                    'name' => $this->examDto->getName(),
                    'subject_id' => $this->examDto->getSubjectId(),
                   "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
            forEach($this->examDto->getQuestions() as $question) {
                $questionId = DB::table('questions')->insertGetId([
                    'number' => $question['number'],
                    'value' => $question['question'],
                    'level' => $question['level'],
                    'exam_id' => $examId,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                 DB::table('options')->insert([
                    'question_id' => $questionId,
                    'value' => $question['option1'],
                    'is_answer' => $question['option1'] === $question['answer'],
                     "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                 DB::table('options')->insert([
                    'question_id' => $questionId,
                    'value' => $question['option2'],
                    'is_answer' => $question['option2'] === $question['answer'],
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
                 DB::table('options')->insert([
                    'question_id' => $questionId,
                    'value' => $question['option3'],
                    'is_answer' => $question['option3'] === $question['answer'],
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
