<?php

namespace App\Service;

use App\Dto\FinishedQuizDto;
use App\Dto\QuizResultDto;
use App\Dto\QuizResultsByQuizIdDto;
use App\Dto\QuizResultsDto;
use App\Entity\Quiz;
use App\Entity\User;
use App\Exception\QuizNotFoundException;
use App\Service\Contract\QuizInterface;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

class QuizManager implements QuizInterface
{
    private User $user;

    public function __construct(
        private readonly QuizCreator $quizCreator,
        private readonly QuizResultFormatter $quizResultFormatter,
        private readonly QuizFinishedResultFormatter $quizFinishedResultFormatter,
        private readonly QuizUpdater $quizUpdater,
        private readonly ResultUpdater $resultUpdater,
        private readonly QuizDataSupplier $quizDataSupplier,
    )
    {
    }

    /**
     * @throws QuizNotFoundException
     */
    public function setUser(UserInterface $user): void
    {
        $this->user = $this->quizDataSupplier->validateUser($user);
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getActiveUserQuizQuestionsWithAnswers(): QuizResultsDto
    {
        $quiz = $this->quizDataSupplier->getActiveQuizWithResultsByUser($this->user);

        return $this->quizResultFormatter->formatQuizResults($quiz->getResults()->toArray(), $quiz);
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getFinishedQuizzesList(): array
    {
        $quizzes = $this->quizDataSupplier->getFinishedQuizzesByUser($this->user);

        $output = [];
        foreach ($quizzes as $quiz) {
            $output[] = new FinishedQuizDto(
                id: $quiz->getId(),
                startedAt: $quiz->getStartedAt() ?->format('d.m.Y')
            );
        }

        return $output;
    }

    /**
     * @throws QuizNotFoundException
     */
    public function updateActiveUserQuizResult(QuizResultDto $dto): void
    {
        $result = $this->quizDataSupplier->getQuizResultById($dto->resultId);

        $this->resultUpdater->updateResult($result, $dto);
        $this->quizUpdater->updateQuiz($result->getQuiz(), $dto->nextResultId);
    }

    public function finishActiveUserQuiz(): void
    {
        $userQuiz = $this->getActiveUserQuizOrNull();
        if ($userQuiz) {
            $this->quizUpdater->finishQuiz($userQuiz);
        }
    }

    public function createUserQuiz(): void
    {
        $this->quizCreator->create(
            user: $this->user,
            createdDate: new DateTimeImmutable()
        );
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getActiveUserQuiz(): Quiz
    {
        $quiz = $this->getActiveUserQuizOrNull();
        if ($quiz === null) {
            throw new QuizNotFoundException();
        }

        return $quiz;
    }

    public function getActiveUserQuizOrNull(): ?Quiz
    {
        return $this->quizDataSupplier->getActiveQuizOrNullByUser($this->user);
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getQuizResultsByQuizId(int $quizId): QuizResultsByQuizIdDto
    {
        $quiz = $this->quizDataSupplier->getQuizWithResultsByQuizIdAndOwner($quizId, $this->user);

        return $this->quizFinishedResultFormatter->formatQuizResultsByQuizId($quiz->getResults()->toArray());
    }
}