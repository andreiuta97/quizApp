<?php


namespace QuizApp\Repository;


use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\AbstractRepository;
use ReallyOrm\SearchResult\SearchResult;

class QuizInstanceRepository extends AbstractRepository
{
    /**
     * Counts how many questions are in a quiz instance.
     *
     * @param int $quizTemplateId
     * @return int
     */
    public function getQuestionsNumber(int $quizTemplateId): int
    {
        $sql = 'SELECT COUNT(id) FROM question_instance WHERE quiz_instance_id = ?';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $quizTemplateId);

        $dbStmt->execute();
        return $dbStmt->fetchColumn();
    }

    /**
     * Retrieves necessary data to display the results from database.
     *
     * @param Criteria $criteria
     * @return SearchResult
     */
    public function getResultsData(Criteria $criteria): SearchResult
    {
        $sql = "SELECT quiz_instance.id AS quiz_instance_id, user.name, quiz_template.id AS quiz_template_id FROM quiz_instance 
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