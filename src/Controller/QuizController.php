<?php

namespace App\Controller;

use App\Exception\QuizException;
use App\Service\Contract\QuizInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    public function __construct(
        private readonly QuizInterface $quiz,
    ) {
    }

    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        $this->quiz->setUser($this->getUser());
        return $this->render('quiz/index.html.twig', [
            'finished_quizzes' => $this->quiz->getFinishedQuizzesList(),
            'active_quiz' => $this->quiz->getActiveUserQuizOrNull(),
        ]);
    }

    #[Route('/start', name: 'app_start')]
    public function start(): Response
    {
        try {
            $this->quiz->setUser($this->getUser());
            $this->quiz->finishActiveUserQuiz();
            $this->quiz->createUserQuiz();
            return $this->redirectToRoute('app_quiz');
        } catch (Exception $e) {
            return $this->catchError($e);
        }
    }

    #[Route('/quiz', name: 'app_quiz')]
    public function quiz(): Response
    {
        try {
            $this->quiz->setUser($this->getUser());
            return $this->render('quiz/quiz.html.twig', [
                'questions' => $this->quiz->getActiveUserQuizQuestionsWithAnswers()
            ]);
        } catch (Exception $e) {
            return $this->catchError($e);
        }
    }

    #[Route('/submit', name: 'app_submit', methods: [Request::METHOD_POST])]
    public function submit(): Response
    {
        try {
            $this->quiz->setUser($this->getUser());
            $quizId = $this->quiz->getActiveUserQuiz()->getId();
            $this->quiz->finishActiveUserQuiz();
            return $this->redirectToRoute('app_result', [
                'quizId' => $quizId,
            ]);
        } catch (Exception $e) {
            return $this->catchError($e);
        }
    }

    #[Route('/result/{quizId}', name: 'app_result')]
    public function result(int $quizId): Response
    {
        try {
            $this->quiz->setUser($this->getUser());
            $quiz = $this->quiz->getQuizResultsByQuizId($quizId);
            return $this->render('quiz/result.html.twig', [
                'quiz' => $quiz,
            ]);
        } catch (Exception $e) {
            return $this->catchError($e);
        }
    }

    private function catchError(Exception $e): Response
    {
        $this->addFlash(
            'error',
            ($e instanceof QuizException)
                ? $e->getMessage()
                : 'Unknown error occurs. Please try again later'
        );
        return $this->redirectToRoute('app_index');
    }
}
