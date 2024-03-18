<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\User;
use App\Exception\QuizNotFoundException;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class QuizDataSupplier
{
    public function __construct(
        private readonly QuizRepository $quizRepository,
        private readonly ResultRepository $resultRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getActiveQuizWithResultsByUser(UserInterface $user): Quiz
    {
        $quiz = $this->quizRepository->findActiveQuizWithResultsByUser($this->validateUser($user));
        if ($quiz === null) {
            throw new QuizNotFoundException();
        }

        return $quiz;
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getFinishedQuizzesByUser(UserInterface $user): array
    {
        return $this->quizRepository->findBy([
            'owner' => $this->validateUser($user),
            'active' => false,
        ]);
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getActiveQuizOrNullByUser(UserInterface $user): ?Quiz
    {
        return $this->quizRepository->findOneBy([
            'owner' => $this->validateUser($user),
            'active' => true,
        ]);
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getQuizWithResultsByQuizIdAndOwner(int $quizId, UserInterface $user): Quiz
    {
        $quiz = $this->quizRepository->findQuizWithResultsByQuizIdAndOwner($quizId, $this->validateUser($user));
        if ($quiz === null) {
            throw new QuizNotFoundException();
        }

        return $quiz;
    }

    /**
     * @throws QuizNotFoundException
     */
    public function getQuizResultById(int $id)
    {
        $result = $this->resultRepository->find($id);
        if ($result === null) {
            throw new QuizNotFoundException();
        }

        return $result;
    }

    /**
     * @throws QuizNotFoundException
     */
    public function validateUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            $user = $this->userRepository->find($user->getUserIdentifier());
        }

        if ($user === null) {
            throw new QuizNotFoundException();
        }

        return $user;
    }
}