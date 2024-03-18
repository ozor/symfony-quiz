<?php

namespace App\Dto;

class QuestionDto
{
    public int $questionId;
    public string $question;
    public bool $isAnswered;

    public function __construct(int $questionId, string $question, bool $isAnswered)
    {
        $this->questionId = $questionId;
        $this->question = $question;
        $this->isAnswered = $isAnswered;
    }
}