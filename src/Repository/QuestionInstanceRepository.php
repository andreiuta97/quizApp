<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    /**
     * Retrieves all questions from the database for a given quiz instance.
     *
     * @param int $quizInstanceId
     * @return array
     */
    public function getQuestions(int $quizInstanceId): array
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE quiz_instance_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $quizInstanceId);
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

    /**
     * Retrieves the number of questions of a given quiz instance.
     *
     * @param int $quizInstanceId
     * @return int
     */
    public function getQuestionsNumber(int $quizInstanceId): int
    {
        $sql = 'SELECT COUNT(id) FROM ' . $this->getTableName() . ' WHERE quiz_instance_id = ?';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $quizInstanceId);
        $dbStmt->execute();

        return $dbStmt->fetchColumn();
    }
}
