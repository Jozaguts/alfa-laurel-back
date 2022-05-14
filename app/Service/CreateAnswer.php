<?php

namespace App\Service;
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
        $this->subject_id = $data[0]['subject_id'];        
        $this->exam_id = $data[0]['exam_id'];
        $this->user_id = $data[0]['user_id'];
        $this->minutes_assigns = $data[0]['minutes_assigns'];
        $this->minutes = $data[0]['minutes'];
        $this->student_code = $data[0]['student_code'];
        $this->student_name = $data[0]['student_name'];
        $this->answers_details = $data[0]['answers_details'];        
    }

    public function execute(){
        DB::beginTransaction();
        try {
            
            $answer = DB::table('answers')->insertGetId([
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
                $det = DB::table('answers_details')->insertGetId([
                    'answer_id' => $detail['answer_id'],
                    'question_id' => $detail['question_id'],
                    'number' => $detail['number'],
                    'question' => $detail['question'],
                    'option1' => $detail['option1'],
                    'option2' => $detail['option2'],
                    'option3' => $detail['option3'],
                    'answer' => $detail['answer'],
                    'is_correct' => $detail['is_correct'],
                    'created_at' => \Carbon\Carbon::now(),
                ]);
            }
            DB::commit();
            return ['success' => true, 'message' => 'success'];            
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['success'=> false, 'message'=> $th->getMessage() . $th->getFile(), $th->getLine()];
        }
         
    }   
}