<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use App\Service\QuizCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class QuizFixtures extends Fixture
{
    private const QUESTIONS_WITH_ANSWERS = [
        [
            'question' => '1 + 1 = ',
            'answers' => [
                ['answer' => '3', 'correct' => false],
                ['answer' => '2', 'correct' => true],
                ['answer' => '0', 'correct' => false],
            ],
        ],
        [
            'question' => '2 + 2 = ',
            'answers' => [
                ['answer' => '4', 'correct' => true],
                ['answer' => '3 + 1', 'correct' => true],
                ['answer' => '10', 'correct' => false],
            ],
        ],
        [
            'question' => '3 + 3 = ',
            'answers' => [
                ['answer' => '1 + 5', 'correct' => true],
                ['answer' => '1', 'correct' => false],
                ['answer' => '6', 'correct' => true],
                ['answer' => '2 + 4', 'correct' => true],
            ],
        ],
        [
            'question' => '4 + 4 = ',
            'answers' => [
                ['answer' => '8', 'correct' => true],
                ['answer' => '4', 'correct' => false],
                ['answer' => '0', 'correct' => false],
                ['answer' => '0 + 8', 'correct' => true],
            ],
        ],
        [
            'question' => '5 + 5 = ',
            'answers' => [
                ['answer' => '6', 'correct' => false],
                ['answer' => '18', 'correct' => false],
                ['answer' => '10', 'correct' => true],
                ['answer' => '9', 'correct' => false],
                ['answer' => '0', 'correct' => false],
            ],
        ],
        [
            'question' => '6 + 6 = ',
            'answers' => [
                ['answer' => '3', 'correct' => false],
                ['answer' => '9', 'correct' => false],
                ['answer' => '0', 'correct' => false],
                ['answer' => '12', 'correct' => true],
                ['answer' => '5 + 7', 'correct' => true],
            ],
        ],
        [
            'question' => '7 + 7 = ',
            'answers' => [
                ['answer' => '5', 'correct' => false],
                ['answer' => '14', 'correct' => true],
            ],
        ],
        [
            'question' => '8 + 8 = ',
            'answers' => [
                ['answer' => '16', 'correct' => true],
                ['answer' => '12', 'correct' => false],
                ['answer' => '9', 'correct' => false],
                ['answer' => '5', 'correct' => false],
            ],
        ],
        [
            'question' => '9 + 9 = ',
            'answers' => [
                ['answer' => '18', 'correct' => true],
                ['answer' => '9', 'correct' => false],
                ['answer' => '17 + 1', 'correct' => true],
                ['answer' => '2 + 16', 'correct' => true],
            ],
        ],
        [
            'question' => '10 + 10 = ',
            'answers' => [
                ['answer' => '0', 'correct' => false],
                ['answer' => '2', 'correct' => false],
                ['answer' => '8', 'correct' => false],
                ['answer' => '20', 'correct' => true],
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::QUESTIONS_WITH_ANSWERS as $item) {
            $question = new Question();
            $question->setQuestion($item['question']);

            foreach ($item['answers'] as $itemAnswer) {
                $answer = new Answer();
                $answer->setAnswer($itemAnswer['answer']);
                $answer->setCorrect($itemAnswer['correct']);
                $manager->persist($answer);

                $question->addAnswer($answer);
            }

            $manager->persist($question);
        }

        $manager->flush();
    }
}
