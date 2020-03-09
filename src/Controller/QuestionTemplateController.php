<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\QuestionTemplateService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionTemplateController extends AbstractController
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuestionTemplateService $questionTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->questionTemplateService = $questionTemplateService;
    }

    public function addQuestion(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->questionTemplateService->add($info);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function getQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->questionTemplateService->getQuestion($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function updateQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->questionTemplateService->update($id, $info);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function deleteQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->questionTemplateService->delete($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }
}
