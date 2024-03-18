<?php

namespace App\Dto;

class QuizResultsDto
{
    public int $quizId;
    public array $current;
    public int $count;
    public array $results;

    public function __construct(int $quizId, array $current, int $count, array $results)
    {
        $this->quizId = $quizId;
        $this->current = $current;
        $this->count = $count;
        $this->results = $results;
    }
}