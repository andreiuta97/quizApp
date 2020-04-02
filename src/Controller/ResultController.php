<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;

class ResultController extends AbstractController
{
    const RESULTS_PER_PAGE = 5;

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
     * Creates a new Criteria entity necessary for displaying the results.
     *
     * @param array $requestAttributes
     * @return Criteria
     */
    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria([], [], $from, self::RESULTS_PER_PAGE);
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
        $paginator = new Paginator($data->getCount(), $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('admin-results-listing.phtml',
            ['data' => $data->getItems(), 'paginator' => $paginator]);
    }
}