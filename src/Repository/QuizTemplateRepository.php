<?php


namespace QuizApp\Repository;

use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'quiz_template';
    }

    public function getNumberOfQuizzes(Criteria $criteria): int
    {
        $sql = 'SELECT count(*) as quizzesNumber FROM quiz_template';
        $sql .= $criteria->toQuerySearch();
        $dbStmt = $this->pdo->prepare($sql);
        $criteria->bindValueToStatementSearch($dbStmt);
        $dbStmt->execute();

        return $dbStmt->fetch(\PDO::FETCH_COLUMN);
    }

    public function findBySearch(Criteria $criteria): array
    {
        $sql = 'SELECT * FROM quiz_template';
        $sql .= $criteria->toQuerySearch();
        $dbStmt = $this->pdo->prepare($sql);
        $criteria->bindValueToStatementSearch($dbStmt);
        $dbStmt->execute();
        $array = $dbStmt->fetchAll();
        $objects = [];
        foreach ($array as $row) {
            $object = $this->hydrator->hydrate($this->entityName, $row);
            $this->hydrator->hydrateId($object, $row['id']);
            $objects[] = $object;
        }

        return $objects;
    }

    public function saveQuestionsForQuiz($id, $questions)
    {
        $sql = 'DELETE FROM quiz_question_template WHERE quiz_template_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();

        foreach ($questions as $questionId) {
            $query = 'INSERT INTO quiz_question_template (quiz_template_id, question_template_id) VALUES (:quizId, :questionId)';
            $sqlStm = $this->pdo->prepare($query);
            $sqlStm->bindParam(':quizId', $id);
            $sqlStm->bindParam('questionId', $questionId);
            $sqlStm->execute();
        }
    }


}