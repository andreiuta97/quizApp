<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuizInstanceService;

class ResultController extends AbstractController
{
    use CriteriaTrait;

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    /**
     * ResultController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $quizInstanceService
     */
    public function __construct(RendererInterface $renderer, QuizInstanceService $quizInstanceService)
    {
        $this->quizInstanceService = $quizInstanceService;
        parent::__construct($renderer);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResults(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes);
        $data = $this->quizInstanceService->getResultsData($criteria);
        $paginator = new Paginator($data->getCount(), $currentPage, self::$resultsPerPage);

        return $this->renderer->renderView('admin-results-listing.phtml',
            ['data' => $data->getItems(), 'paginator' => $paginator]);
    }
}
