<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Psr\Http\Message\RequestInterface;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\User;
use QuizApp\Repository\QuestionTemplateRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class UserController extends AbstractController
{

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
    }

    public function getLogin(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }

    public function toGoTo(Request $request, array $requestAttributes): Response
    {
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');

        $userRepo = $this->repositoryManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $email, 'password' => $password]);
        if(!$user){
            return $this->renderer->renderView('exceptions-page.phtml', $requestAttributes);
        }
        if($user->getRole()==='Admin'){
            return $this->renderer->renderView('admin-dashboard.phtml', ['user' => $user]);
        }

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['user' => $user]);
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-quizzes-listing.phtml', $requestAttributes);
    }

    public function getUsers(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-users-listing.phtml', $requestAttributes);
    }

    public function getResults(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-results-listing.phtml', $requestAttributes);
    }

    public function logout(Request $request, array $requestAttributes):Response
    {
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }

}
