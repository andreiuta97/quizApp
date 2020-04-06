<?php


namespace QuizApp\Repository;


use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\AbstractRepository;
use ReallyOrm\SearchResult\SearchResult;

class QuizInstanceRepository extends AbstractRepository
{
    /**
     * Retrieves necessary data to display the results from database.
     *
     * @param Criteria $criteria
     * @return SearchResult
     */
    public function getResultsData(Criteria $criteria): SearchResult
    {
        $sql = "SELECT quiz_instance.id AS quiz_instance_id, user.name, quiz_template.id AS quiz_template_id, user.id AS user_id FROM quiz_instance 
                    JOIN user ON quiz_instance.user_id = user.id 
                    JOIN quiz_template ON quiz_instance.quiz_template_id = quiz_template.id";
        $sql .= $criteria->toQuery();
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();
        $objects = $dbStmt->fetchAll();

        $searchResult = new SearchResult();
        $searchResult->setItems($objects);
        $searchResult->setTotalCount($this->getNumberOfObjects($criteria));

        return $searchResult;
    }
}