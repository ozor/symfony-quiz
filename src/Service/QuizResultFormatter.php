<?php

namespace App\Service;

use App\Dto\AnswerDto;
use App\Dto\QuestionDto;
use App\Dto\QuizResultsDto;
use App\Dto\ResultDto;
use App\Entity\Quiz;
use Symfony\Component\Serializer\SerializerInterface;

class QuizResultFormatter
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    )
    {
    }

    public function formatQuizResults(array $results, Quiz $quiz): QuizResultsDto
    {
        $currentResultId = $quiz->getCurrentResultId();

        $i = $currentResultIndex = 0;
        $outputResults = [];

        foreach ($results as $result) {
            /** @var QuestionDto $question */
            $question = $this->serializer->denormalize($result->getQuestion(), QuestionDto::class);

            /** @var AnswerDto[] $answers */
            $answers = $this->serializer->denormalize($result->getAnswers(), AnswerDto::class.'[]');

            $outputResults[] = new ResultDto($result->getId(), $question, $answers);

            if ($currentResultId && $currentResultId === $result->getId()) {
//                var_dump($currentResultId, $currentResultIndex, $i); exit;
                $currentResultIndex = $i;
            }
            $i++;
        }

        return new QuizResultsDto(
            $quiz->getId(),
            [
                'id' => $currentResultId,
                'index' => $currentResultIndex ?? null,
            ],
            count($results),
            $outputResults
        );
    }
}