<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getNumberOfUsers(): int
    {
        $sql = 'SELECT count(*) FROM user';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();

        return $dbStmt->fetch(\PDO::FETCH_COLUMN);
    }
}