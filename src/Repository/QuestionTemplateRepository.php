<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'question_template';
    }
}