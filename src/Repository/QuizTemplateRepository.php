<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'quiz_template';
    }
}