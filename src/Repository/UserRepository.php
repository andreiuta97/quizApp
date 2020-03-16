<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getNumberOfUsers(): array
    {
        $sql = 'SELECT count(*) as usersNumber FROM user';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();

        return $dbStmt->fetch();
    }
}