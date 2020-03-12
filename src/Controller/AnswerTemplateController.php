<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use QuizApp\Service\AnswerTemplateService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AnswerTemplateController extends AbstractController
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var AnswerTemplateService
     */
    private $answerTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        AnswerTemplateService $answerTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->answerTemplateService = $answerTemplateService;
    }
}