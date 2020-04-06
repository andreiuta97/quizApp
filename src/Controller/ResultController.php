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
     * @var int
     */
    private $resultsPerPage;

    /**
     * ResultController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $quizInstanceService
     * @param int $resultsPerPage
     */
    public function __construct
    (
        RendererInterface $renderer,
        QuizInstanceService $quizInstanceService,
        int $resultsPerPage
    ) {
        parent::__construct($renderer);
        $this->quizInstanceService = $quizInstanceService;
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResults(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $data = $this->quizInstanceService->getResultsData($criteria);
        $paginator = new Paginator($data->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-results-listing.phtml',
            ['data' => $data->getItems(), 'paginator' => $paginator]);
    }
}
