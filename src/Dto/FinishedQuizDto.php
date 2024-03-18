<?php

namespace App\Dto;

readonly class FinishedQuizDto
{
    public function __construct(
        private int    $id,
        private ?string $startedAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartedAt(): ?string
    {
        return $this->startedAt;
    }
}