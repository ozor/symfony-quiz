<?php

namespace App\Service;

use App\Dto\AnswerDto;
use App\Dto\FinishedQuizDto;
use App\Dto\FinishedResultDto;
use App\Dto\QuestionDto;
use App\Dto\QuizResultsByQuizIdDto;
use App\Dto\QuizResultsDto;
use App\Dto\ResultDto;
use App\Entity\Quiz;
use Symfony\Component\Serializer\SerializerInterface;

class QuizFinishedResultFormatter
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    )
    {
    }

    public function formatQuizResultsByQuizId(array $results): QuizResultsByQuizIdDto
    {
        $quizId = null;
        $correctResults = [];
        $wrongResults = [];

        foreach ($results as $result) {
            $finishedResultDto = $this->createFinishedResultDto($result);

//            echo '<pre>';
//            var_dump($finishedResultDto);
//            echo '</pre><br><br>';

            if ($this->isCorrectAnswer($result->getAnswers())) {
                $correctResults[] = $finishedResultDto;
            } else {
                $wrongResults[] = $finishedResultDto;
            }

            if (!$quizId) {
                $quizId = $result->getQuiz()->getId();
            }
        }
//        exit;

        return new QuizResultsByQuizIdDto(
            quizId: (int)$quizId,
            correctResults: $correctResults,
            wrongResults: $wrongResults
        );
    }

    private function createFinishedResultDto($result): FinishedResultDto
    {
        $question = $result->getQuestion();
        /** @var AnswerDto[] $answers */
        $answers = $this->serializer->denormalize($result->getAnswers(), AnswerDto::class.'[]');

        return new FinishedResultDto(
            question: $question['question'],
            answers: $answers,
            numChooses: $this->countChooses($result->getAnswers())
        );
    }

    private function isCorrectAnswer(array $answers): bool
    {
        if ($this->countChooses($answers) === 0) {
            return false;
        }

        foreach ($answers as $answer) {
            if ($answer['isChoosed'] === true && $answer['isCorrect'] === false) {
                return false;
            }
        }

        return true;
    }

    private function countChooses(array $answers): int
    {
        return array_reduce($answers, function ($carry, $answer) {
            return $carry + ($answer['isChoosed'] === true ? 1 : 0);
        }, 0);
    }
}