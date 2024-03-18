<?php

namespace App\Service;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

class QuizUpdater
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function updateQuiz(Quiz $quiz, ?int $resultId): void
    {
        $quiz->setCurrentResultId($resultId);
        $this->entityManager->flush();
    }

    public function finishQuiz(Quiz $quiz): void
    {
        if ($quiz->getStartedAt() === null) {
            $quiz->setStartedAt(new DateTimeImmutable());
        }
        $quiz->setActive(false);
        $quiz->setCurrentResultId(null);

        $this->entityManager->flush();
    }
}