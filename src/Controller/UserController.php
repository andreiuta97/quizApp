<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Psr\Http\Message\RequestInterface;
use QuizApp\Entity\User;
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

    public function getLogin(RequestInterface $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('login.html', $requestAttributes);
    }

    public function toGoTo(RequestInterface $request, array $requestAttributes): Response
    {
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');

        $userRepo = $this->repositoryManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $email, 'password' => $password]);
        if(!$user){
            return $this->renderer->renderView('exceptions-page.html', $requestAttributes);
        }
        if($user->getRole()==='Admin'){
            return $this->renderer->renderView('admin-dashboard.html', $requestAttributes);
        }

        return $this->renderer->renderView('candidate-quiz-listing.html', $requestAttributes);
    }

    public function getAdminDashboard(RequestInterface $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-dashboard.html', $requestAttributes);
    }

}