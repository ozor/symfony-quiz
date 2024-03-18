<?php

namespace App\Dto;

class AnswerDto
{
    public int $answerId;
    public string $answer;
    public bool $isChoosed;
    public bool $isCorrect;

    public function __construct(int $answerId, string $answer, bool $isChoosed, bool $isCorrect)
    {
        $this->answerId = $answerId;
        $this->answer = $answer;
        $this->isChoosed = $isChoosed;
        $this->isCorrect = $isCorrect;
    }
}