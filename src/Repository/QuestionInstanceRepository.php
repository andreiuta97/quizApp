<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function getQuestions(int $quizId): array
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE quiz_instance_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $quizId);
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
}