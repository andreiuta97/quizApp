<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;

class ResultController extends AbstractController
{
    private $quizInstanceService;

    public function __construct(RendererInterface $renderer, QuizInstanceService $quizInstanceService)
    {
        $this->quizInstanceService = $quizInstanceService;
        parent::__construct($renderer);
    }

    public function getResults(Request $request, array $requestAttributes): Response
    {
        $data = $this->quizInstanceService->getResultsData();

        return $this->renderer->renderView('admin-results-listing.phtml', ['data' => $data]);
    }
}