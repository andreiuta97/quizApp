<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuizInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'quiz_instance';
    }
}