<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\AuthenticationService;
use QuizApp\Service\Paginator;
use QuizApp\Service\UserService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class UserController extends AbstractController
{
    const RESULTS_PER_PAGE = 5;
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        UserService $userService,
        AuthenticationService $authenticationService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->userService = $userService;
        $this->authenticationService = $authenticationService;
    }

    public function adminDashboard()
    {
        $user = $this->authenticationService->getLoggedUser();

        if ($user === null) {
            throw new \Exception("Not logged");
        }

        if ($user->getRole() !== 'Admin') {
            throw new \Exception("Not admin");
        }

        return $this->renderer->renderView('admin-dashboard.phtml', ['user' => $user]);
    }

    public function candidateHomepage()
    {
        $user = $this->authenticationService->getLoggedUser();

        if ($user === null) {
            throw new \Exception("Not logged");
        }

        if ($user->getRole() !== 'Candidate') {
            throw new \Exception("Not candidate");
        }

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['user' => $user]);
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

    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $filters = isset($requestAttributes['role']) ? ['role' => $requestAttributes['role']] : [];
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria($filters, [], $from, self::RESULTS_PER_PAGE);
    }

    public function getUsers(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes);
        $userSearchResult = $this->userService->getUsers($criteria);
        $paginator = new Paginator($userSearchResult->getCount(), $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('admin-users-listing.phtml',
            ['users' => $userSearchResult->getItems(), 'paginator' => $paginator]);
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
