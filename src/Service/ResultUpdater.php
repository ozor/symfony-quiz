<?php

namespace App\Service;

use App\Entity\Result;
use App\Dto\QuizResultDto;
use Doctrine\ORM\EntityManagerInterface;

class ResultUpdater
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function updateResult(Result $result, QuizResultDto $dto): void
    {
        $question = $result->getQuestion();
        $question['isAnswered'] = true;
        $result->setQuestion($question);

        $answers = $result->getAnswers();
        foreach ($answers as $index => $answer) {
            $answers[$index]['isChoosed'] = in_array($answer['answerId'], $dto->answers);
        }
        $result->setAnswers($answers);

        $this->entityManager->flush();
    }
}