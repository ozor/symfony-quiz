<?php

namespace App\Dto;

readonly class FinishedResultDto
{
    public function __construct(
        private string $question,
        private array $answers,
        private int $numChooses
    ) {}

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getNumChooses(): int
    {
        return $this->numChooses;
    }
}