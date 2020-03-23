<?php


namespace QuizApp\Service;


class Paginator
{
    private $totalResults;
    private $totalPages;
    private $currentPage;
    private $resultsPerPage;

    public function __construct
    (
        int $totalResults,
        int $currentPage = 1,
        int $resultsPerPage = 5
    )
    {
        $this->totalResults = $totalResults;
        $this->setCurrentPage($currentPage);
        $this->setResultsPerPage($resultsPerPage);
    }

    /**
     * Sets the number of results that should be displayed on a page
     * and updates the number of available pages accordingly.
     *
     * @param int $resultsPerPage
     */
    public function setResultsPerPage(int $resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->setTotalPages($this->totalResults, $resultsPerPage);
    }

    /**
     * Calculates the number of available pages.
     *
     * @param int $totalResults
     * @param int $resultsPerPage
     */
    private function setTotalPages(int $totalResults, int $resultsPerPage)
    {
        $this->totalPages = (int)ceil($totalResults / $resultsPerPage);
    }

    /**
     * Sets the current page, ensuring that only non-negative and
     * non-zero values are possible.
     *
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage <= 0 ? 1 : $currentPage;
    }

    /**
     * Returns the next page number or null if there are no more
     * pages available.
     *
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        if ($this->currentPage < $this->totalPages) {
            return $this->currentPage + 1;
        }
        return null;
    }

    /**
     * Returns the offset of the current page.
     *
     * @param int $currentPage
     * @return float|int
     */
    public function getOffsetOfPage(int $currentPage)
    {
        return ($currentPage - 1) * $this->resultsPerPage;
    }

    /**
     * Returns the current page.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Returns the total number of pages.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Returns the total number of objects.
     *
     * @return int
     */
    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    /**
     * Return the number of objects displayed on a page.
     *
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }
}