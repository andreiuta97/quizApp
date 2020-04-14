<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\AuthenticationService;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\UserService;
use ReallyOrm\Repository\RepositoryManagerInterface;

class UserController extends AbstractController
{
    use CriteriaTrait;
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
    /**
     * @var int
     */
    private $resultsPerPage;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        UserService $userService,
        AuthenticationService $authenticationService,
        SessionInterface $session,
        int $resultsPerPage
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->userService = $userService;
        $this->authenticationService = $authenticationService;
        $this->session = $session;
        $this->resultsPerPage = $resultsPerPage;
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

        return $this->createRedirectResponse('/listUsers');
    }

    public function updateUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->userService->update($id, $info);

        return $this->createRedirectResponse('/listUsers');
    }

    public function deleteUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        if ($id === $this->session->get('id')) {
            //TODO Make it prettier when introducing validations
            throw new \Exception("Cannot delete yourself");
        }
        $this->userService->delete($id);

        return $this->createRedirectResponse('/listUsers');
    }

    public function getUsers(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $userSearchResult = $this->userService->getUsers($criteria);
        $paginator = new Paginator($userSearchResult->getCount(), $currentPage, $this->resultsPerPage);
        $sessionUserId = $this->session->get('id');

        return $this->renderer->renderView
        (
            'admin-users-listing.phtml',
            [
                'users' => $userSearchResult->getItems(),
                'paginator' => $paginator,
                'sessionUserId' => $sessionUserId
            ]
        );
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
