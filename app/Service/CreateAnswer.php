<?php

namespace App\Service;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class CreateAnswer
{

    private int $subject_id;
    private int $exam_id;
    private int $user_id;
    private int $minutes_assigns;
    private int $minutes;
    private string $student_code;
    private string $student_name;
    private $answers_details;

    public function __construct($data)
    {        
        $this->subject_id = $data['subject_id'];        
        $this->exam_id = $data['exam_id'];
        $this->user_id = $data['user_id'];
        $this->minutes_assigns = $data['minutes_assigns'];
        $this->minutes = $data['minutes'];
        $this->student_code = $data['student_code'];
        $this->student_name = $data['student_name'];
        $this->answers_details = $data['answers_details'];        
    }

    public function execute(){
        DB::beginTransaction();
        try {
            $answerId = DB::table('answers')->insertGetId([
                'subject_id' => $this->subject_id,
                'exam_id' => $this->exam_id, 
                'user_id' => $this->user_id,
                'minutes_assigns' => $this->minutes_assigns,
                'minutes' => $this->minutes,
                'student_code' => $this->student_code,
                'student_name' => $this->student_name,
                'created_at' => \Carbon\Carbon::now(),
            ]);
            

            foreach($this->answers_details as $detail){
                $correcta= ($detail['answer'] == Question::find(1)->answer) ? true : false;
        
                $det = DB::table('answer_details')->insertGetId([
                    'answer_id' => $answerId,
                    'question_id' => $detail['question_id'],
                    'number' => $detail['number'],
                    'question' => $detail['question'],
                    'option1' => $detail['option1'],
                    'option2' => $detail['option2'],
                    'option3' => $detail['option3'],
                    'answer' => $detail['answer'],
                    'level' => $detail['level'],
                    'is_correct' => ($detail['answer'] == Question::find(1)->answer) ? true : false,
                    'created_at' => \Carbon\Carbon::now(),
                ]);
            }
            DB::commit();
            // DB::rollBack();
            return ['success' => true, 'message' => 'success'];            
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['success'=> false, 'message'=> $th->getMessage() . $th->getFile(), $th->getLine()];
        }
         
    }   
}