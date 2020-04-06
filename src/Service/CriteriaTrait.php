<?php


namespace QuizApp\Service;


use ReallyOrm\Criteria\Criteria;

trait CriteriaTrait
{
    /**
     * @var int
     */
    static $results_per_page = 5;

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
     * @return Criteria
     */
    public function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $filters = $this->getFiltersForCriteria($requestAttributes);
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::$results_per_page;

        return new Criteria($filters, [], $from, self::$results_per_page);
    }
}
