<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Entity\User;
use QuizApp\Service\AuthenticationService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AuthenticationController extends AbstractController
{
    private $repositoryManager;
    private $authenticationService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        AuthenticationService $authenticationService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->authenticationService = $authenticationService;
    }

    public function getLogin(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }

    public function toGoTo(Request $request, array $requestAttributes): Response
    {

        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $this->authenticationService->login($email, $password);
        $userRepo = $this->repositoryManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $email, 'password' => $password]);

        if ($role === 'Admin') {
            return $this->renderer->renderView('admin-dashboard.phtml', ['user' => $user]);
        }
        if ($role === 'Candidate') {
            return $this->renderer->renderView('candidate-quiz-listing.phtml', ['user' => $user]);
        }
    }

    public function logout(Request $request, array $requestAttributes): Response
    {
        $this->authenticationService->logout();
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }
}