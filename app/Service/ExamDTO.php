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
    private int $counterLow;
    private int $counterMedium;
    private int $counterHigh;
    const ANSWER_INDEX = 6, NUMBER_INDEX = 0, QUESTION_INDEX = 1, LEVEL_INDEX = 2;
    public function __construct($data)
    {

        $this->name = $data['name'];
        $this->subject_id = $data['subject_id'];
        $this->user_id = $data['user_id'];
        $this->counterLow = $data['low'];
        $this->counterMedium = $data['medium'];
        $this->counterHigh = $data['high'];
        request()->hasFile('file')
            ? $this->questions = request()->file('file')
            : $this->questions = $data['questions'];
    }

    /**
     * @return int|mixed
     */
    public function getCounterLow(): mixed
    {
        return $this->counterLow;
    }

    /**
     * @return int|mixed
     */
    public function getCounterMedium(): mixed
    {
        return $this->counterMedium;
    }

    /**
     * @return int|mixed
     */
    public function getCounterHigh(): mixed
    {
        return $this->counterHigh;
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

          $counterLow =   count(array_filter($this->questions, function($question) {return $question['level'] == 'B';}));
          $counterMedium =   count(array_filter($this->questions, function($question) {return $question['level'] == 'M';}));
          $counterHigh =   count(array_filter($this->questions, function($question) {return $question['level'] == 'A';}));

        if ($counterLow < (int) $this->counterLow) {
            abort('400',"El numero de preguntas en nivel BAJO ({$this->counterLow}) que quieres asignar es mayor a la cantidad disponible ({$counterLow})");
        }
        if ($counterMedium < (int) $this->counterMedium) {
            abort('400',"El numero de preguntas en nivel MEDIO ({$this->counterMedium}) que quieres asignar es mayor a la cantidad disponible ({$counterMedium})");
        }
        if ($counterHigh < (int) $this->counterHigh) {
            abort('400',"El numero de preguntas en nivel ALTO  ({$this->counterHigh}) que quieres asignar es mayor a la cantidad disponible ({$counterHigh})");
        }

        return $this->questions;
    }
    private function initReader(): \PhpOffice\PhpSpreadsheet\Spreadsheet|string
    {
        $reader = match ($this->questions->extension()) {
            'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(),
            'xls' => new \PhpOffice\PhpSpreadsheet\Reader\Xls(),
            default => abort(401,"El formato: {$this->questions->extension()} del archivo no es soportado"),
        };

        $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
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
                    'number' => 1,
                ];
                $options[] = [
                    'option' => $question[4],
                    'is_answer' => $question[6] === 2,
                    'number' => 2,
                ];
                $options[] = [
                    'option' => $question[5],
                    'is_answer' => $question[6] === 3,
                    'number' => 3,
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
