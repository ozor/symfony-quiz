<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\Table(name: '`result`')]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\Column(type: Types::JSON, options: ['jsonb' => true, 'default' => '[]'])]
    #[Assert\All(constraints: [
        new Assert\Collection(fields: [
            'answerId' => [
                new Assert\Type(type: 'int')
            ],
            'answer' => [
                new Assert\Type(type: 'string')
            ],
            'isCorrect' => [
                new Assert\Type(type: 'bool'),
            ],
            'isChoosed' => [
                new Assert\Type(type: 'bool'),
            ]
        ])
    ])]
    private array $answers = [];

    #[ORM\Column(type: Types::JSON, options: ['jsonb' => true, 'default' => '[]'])]
    #[Assert\All(constraints: [
        new Assert\Collection(fields: [
            'questionId' => [
                new Assert\Type(type: 'int')
            ],
            'question' => [
                new Assert\Type(type: 'string')
            ],
            'isAnswered' => [
                new Assert\Type(type: 'bool')
            ]
        ])
    ])]
    private array $question = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): static
    {
        $this->answers = $answers;

        return $this;
    }

    public function getQuestion(): array
    {
        return $this->question;
    }

    public function setQuestion(array $question): static
    {
        $this->question = $question;

        return $this;
    }
}
