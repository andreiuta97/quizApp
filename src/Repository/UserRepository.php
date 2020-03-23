<?php


namespace QuizApp\Repository;

use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getNumberOfUsers(Criteria $criteria): int
    {
        $sql = 'SELECT count(*) FROM user';
        $sql .= $criteria->toQuery();
        $dbStmt = $this->pdo->prepare($sql);
        $criteria->bindParamsToStatement($dbStmt);
        $dbStmt->execute();

        return $dbStmt->fetch(\PDO::FETCH_COLUMN);
    }
}