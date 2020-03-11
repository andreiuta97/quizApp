<?php


namespace QuizApp\Controller;


use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;

class ResultController extends AbstractController
{
    public function getResults(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-results-listing.phtml', $requestAttributes);
    }
}