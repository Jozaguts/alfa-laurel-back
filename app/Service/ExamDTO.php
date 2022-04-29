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
    const ANSWER_INDEX = 6, OPTION_1_INDEX = 3, OPTION_2_INDEX = 4, OPTION_3_INDEX = 5;
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

        return array_map(function($question){

            $question[self::ANSWER_INDEX] = match($question[self::ANSWER_INDEX]) {
                1, => $question[self::OPTION_1_INDEX],
                2, => $question[self::OPTION_2_INDEX],
                3, => $question[self::OPTION_3_INDEX],
            };
            return array_combine(
                ['number','question','level','option1','option2','option3','answer'],
                $question
            );
        },$arrayQuestion);

    }


}
