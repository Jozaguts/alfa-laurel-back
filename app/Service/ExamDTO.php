<?php

namespace App\Service;

use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class ExamDTO
{
    public string $name;
    public int $subject_id;
    public int $user_id;
    public mixed $questions;
    const ANSWER_INDEX = 6, NUMBER_INDEX = 0, QUESTION_INDEX = 1, LEVEL_INDEX = 2;
    public function __construct($data)
    {

        $this->name = $data['name'];
        $this->subject_id = $data['subject_id'];
        $this->user_id = $data['user_id'];
        request()->hasFile('file')
            ? $this->questions = request()->file('file')
            : $this->questions = $data['questions'];
    }

    /**
     * @return mixed|string
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @return int|mixed
     */
    public function getSubjectId(): mixed
    {
        return $this->subject_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return array|mixed
     */
    public function getQuestions(): mixed
    {
        if ( $this->questions instanceof UploadedFile) {
            $spreadsheet = $this->initReader();
            $arrayQuestion = $spreadsheet->getActiveSheet()->toArray();
            unset($arrayQuestion[0]);
           $this->questions = $this->questionNormalized($arrayQuestion);
        }
        return $this->questions;
    }
    private function initReader(): \PhpOffice\PhpSpreadsheet\Spreadsheet|string
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        try {
            return $reader->load($this->questions);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    private function questionNormalized($arrayQuestion): array
    {
        // solo para archivos excel
        $options = [];
            return array_map(function($question) use ($options){
                $options[] = [
                    'option' => $question[3],
                    'is_answer' => $question[6] === 1,
                ];
                $options[] = [
                    'option' => $question[4],
                    'is_answer' => $question[6] === 2,
                ];
                $options[] = [
                    'option' => $question[5],
                    'is_answer' => $question[6] === 3,
                ];
              return [
                  'number' => $question[self::NUMBER_INDEX],
                  'question' => $question[self::QUESTION_INDEX],
                  'level' => $question[self::LEVEL_INDEX],
                  'answer' => $question[self::ANSWER_INDEX],
                  'options' => $options
              ];
            },$arrayQuestion);



    }


}
