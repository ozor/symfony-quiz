<?php

namespace App\Dto;

class QuizResultDto
{
    public function __construct(
        public readonly int $resultId,
        public readonly ?int $nextResultId,
        /** @var int[] $answers */
        public readonly array $answers,
    )
    {
    }
}