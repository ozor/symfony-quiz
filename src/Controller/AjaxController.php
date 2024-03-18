<?php

namespace App\Controller;

use App\Dto\QuizResultDto;
use App\Service\Contract\QuizInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ajax')]
class AjaxController extends AbstractController
{
    public function __construct(
        private readonly QuizInterface $quiz,
    ) {
    }

    #[Route('/update', name: 'app_update', methods: [Request::METHOD_POST])]
    public function update(Request $request): Response
    {
        try {
            $this->quiz->setUser($this->getUser());
            $this->quiz->updateActiveUserQuizResult(
                new QuizResultDto(
                    resultId: $request->request->get('resultId'),
                    nextResultId: $request->request->get('nextResultId') ?: null,
                    answers: $request->request->all('answers')
                )
            );
            return $this->json('OK');
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
    }
}
