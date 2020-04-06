<?php


namespace QuizApp\Service;


use ReallyOrm\Criteria\Criteria;

trait CriteriaTrait
{
    /**
     * Constructs the array of filters necessary to create a new Criteria instance
     *
     * @param array $requestAttributes
     * @return array
     */
    private function getFiltersForCriteria(array $requestAttributes): array
    {
        $filters = [];
        if (empty($requestAttributes)) {
            $filters = [];
        }
        foreach ($requestAttributes as $key => $value) {
            if ($key !== 'page') {
                $filters = isset($requestAttributes[$key]) ? [$key => $requestAttributes[$key]] : [];
            }
        }

        return $filters;
    }

    /**
     * Creates a new Criteria instance from request attributes.
     * Function called in controllers to retrieve the list of entities.
     *
     * @param array $requestAttributes
     * @param int $resultsPerPage
     * @return Criteria
     */
    public function getCriteriaFromRequest(array $requestAttributes, int $resultsPerPage): Criteria
    {
        $filters = $this->getFiltersForCriteria($requestAttributes);
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * $resultsPerPage;

        return new Criteria($filters, [], $from, $resultsPerPage);
    }
}
