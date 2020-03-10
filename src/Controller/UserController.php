<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\User;
use QuizApp\Service\UserService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class UserController extends AbstractController
{

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    private $userService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        UserService $userService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->userService=$userService;
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

    public function getResults(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-results-listing.phtml', $requestAttributes);
    }

    public function logout(Request $request, array $requestAttributes):Response
    {
        return $this->renderer->renderView('login.phtml', $requestAttributes);
    }









    public function addUser(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->userService->add($info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listUsers');

        return $response;
    }

    public function getUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->userService->getUser($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function updateUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->userService->update($id, $info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listUsers');

        return $response;
    }

    public function deleteUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->userService->delete($id);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listUsers');

        return $response;
    }

    public function getUsers(Request $request, array $requestAttributes): Response
    {
        $userRepo = $this->repositoryManager->getRepository(User::class);
        // get filters and pagination from request
        $criteria = new Criteria();
        $users = $userRepo->findBy($criteria);
        $users = ['users' => $users];
        return $this->renderer->renderView('admin-users-listing.phtml', $users);
    }

    public function addNewUser(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-user-add.phtml', $requestAttributes);
    }

    public function editUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $user = $this->userService->getUser($id);

        return $this->renderer->renderView('admin-user-edit.phtml', ['user' => $user]);
    }

}
