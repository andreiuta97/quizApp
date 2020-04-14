<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\AnswerInstanceService;
use QuizApp\Service\AnswerTemplateService;

class AnswerInstanceController extends AbstractController
{
    /**
     * @var AnswerInstanceService
     */
    private $answerInstanceService;

    public function __construct
    (
        RendererInterface $renderer,
        AnswerInstanceService $answerInstanceService
    )
    {
        parent::__construct($renderer);
        $this->answerInstanceService = $answerInstanceService;
    }

    public function saveAnswerInstance(Request $request, array $requestAttributes)
    {
        $answerId = $requestAttributes['id'];
        $offset = $requestAttributes['offset'];
        $answerText = $request->getParameter('answer');
        $this->answerInstanceService->save($answerId, $answerText);

        return $this->createRedirectResponse('/quiz/question/'.((int)$offset+1));
    }

    public function submitQuiz(Request $request, array $requestAttributes)
    {
        $answerId = $requestAttributes['id'];
        $answerText = $request->getParameter('answer');
        $this->answerInstanceService->save($answerId, $answerText);

        return $this->createRedirectResponse('/candidate/overview');
    }
}