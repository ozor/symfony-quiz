<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

readonly class QuizCreator
{
    public function __construct(
        private QuestionRepository     $questionRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function create(User $user, DateTimeImmutable $createdDate): void
    {
        $quiz = new Quiz();
        $quiz->setActive(true);
        $quiz->setOwner($user);
        $quiz->setStartedAt($createdDate);

        $this->entityManager->persist($quiz);

        $questions = $this->questionRepository->findAll();

        $firstResult = null;
        foreach ($questions as $question) {
            $result = new Result();
            $result->setQuestion([
                'questionId' => $question->getId(),
                'question' => $question->getQuestion(),
                'isAnswered' => false,
            ]);
            $result->setQuiz($quiz);

            if ($firstResult === null) {
                $firstResult = $result;
            }

            $answerData = [];
            foreach ($question->getAnswers() as $answer) {
                $answerData[] = [
                    'answerId' => $answer->getId(),
                    'answer' => $answer->getAnswer(),
                    'isCorrect' => $answer->isCorrect(),
                    'isChoosed' => false,
                ];
            }

            $result->setAnswers($answerData);

            $this->entityManager->persist($result);
        }

        $this->entityManager->flush();

        $quiz->setCurrentResultId($firstResult->getId());
        $this->entityManager->flush();
    }
}