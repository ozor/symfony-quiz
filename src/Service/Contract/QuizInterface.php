<?php

namespace App\Service\Contract;

use App\Dto\QuizResultsByQuizIdDto;
use App\Dto\QuizResultsDto;
use App\Entity\Quiz;

interface QuizInterface
{
    public function getActiveUserQuizQuestionsWithAnswers(): QuizResultsDto;

//    public function saveActiveUserQuizAnswer(int $answerId): void;

    public function finishActiveUserQuiz(): void;

    public function getActiveUserQuiz(): Quiz;

    public function getQuizResultsByQuizId(int $quizId): QuizResultsByQuizIdDto;

    public function getFinishedQuizzesList(): array;
}