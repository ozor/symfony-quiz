<?php

namespace App\Dto;

readonly class QuizResultsByQuizIdDto
{
    public function __construct(
        private int $quizId,
        private array $correctResults,
        private array $wrongResults
    )
    {
    }

    public function getQuizId(): int
    {
        return $this->quizId;
    }

    public function getCorrectResults(): array
    {
        return $this->correctResults;
    }

    public function getWrongResults(): array
    {
        return $this->wrongResults;
    }
}