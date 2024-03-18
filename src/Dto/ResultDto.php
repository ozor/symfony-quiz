<?php

namespace App\Dto;

class ResultDto
{
    public int $resultId;
    public QuestionDto $question;
    /** @var AnswerDto[] $answers */
    public array $answers;

    public function __construct(int $resultId, QuestionDto $question, array $answers)
    {
        $this->resultId = $resultId;
        $this->question = $question;
        $this->answers = $answers;
    }
}