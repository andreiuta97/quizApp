<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizTemplateService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizTemplateController extends AbstractController
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    private $quizTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateService $quizTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizTemplateService = $quizTemplateService;
    }

    public function addQuiz(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->quizTemplateService->add($info);

        //return response
    }

    public function getQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->getQuiz($id);

        //return response
    }

    public function updateQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->quizTemplateService->update($id, $info);

        //return response
    }

    public function deleteQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->delete($id);

        //return response
    }
}