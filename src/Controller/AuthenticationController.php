<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\User;
use QuizApp\Service\AuthenticationService;
use QuizApp\Service\HashingService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AuthenticationController extends AbstractController
{
    private $repositoryManager;
    private $authenticationService;
    /**
     * @var HashingService
     */
    private $hashingService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        AuthenticationService $authenticationService,
        HashingService $hashingService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->authenticationService = $authenticationService;
        $this->hashingService = $hashingService;
    }

    public function getLogin(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }

    public function redirectAtLogin(Request $request, array $requestAttributes): Response
    {
        //$this->session=$this
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $this->authenticationService->login($email, $password);
        $userRepo = $this->repositoryManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $email]);
        if (!$this->hashingService->verify($password, $user->getPassword())) {
            return null;
        }

        if ($role === 'Admin') {
            return $this->createRedirectResponse('/admin/dashboard');
        }
        if ($role === 'Candidate') {
            return $this->createRedirectResponse('/candidate/homepage');
        }
    }

    public function logout(Request $request, array $requestAttributes): Response
    {
        $this->authenticationService->logout();
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }
}