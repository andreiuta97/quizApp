<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
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

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        UserService $userService,
        AuthenticationService $authenticationService,
        int $resultsPerPage
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->userService = $userService;
        $this->authenticationService = $authenticationService;
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * Displays the admin dashboard page.
     *
     * @return Response
     * @throws \Exception
     */
    public function showAdminDashboard(): Response
    {
        $user = $this->authenticationService->getLoggedUser();

        if ($user === null) {
            //TODO
            throw new \Exception("Not logged");
        }

        if ($user->getRole() !== 'Admin') {
            //TODO
            throw new \Exception("Not admin");
        }

        return $this->renderer->renderView('admin-dashboard.phtml', ['user' => $user]);
    }

    /**
     * Displays the candidate homepage.
     *
     * @return Response
     * @throws \Exception
     */
    public function showCandidateHomepage(): Response
    {
        $user = $this->authenticationService->getLoggedUser();

        if ($user === null) {
            //TODO
            throw new \Exception("Not logged");
        }

        if ($user->getRole() !== 'Candidate') {
            //TODO
            throw new \Exception("Not candidate");
        }

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['user' => $user]);
    }

    /**
     * Adds an user to database and redirects to "Users Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addUser(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->userService->add($info);

        return $this->createRedirectResponse('/listUsers');
    }

    /**
     * Updates the selected user from the database and redirects to "Users Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function updateUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->userService->update($id, $info);

        return $this->createRedirectResponse('/listUsers');
    }

    /**
     * Deletes the selected user from the database and redirects to "Users Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->userService->delete($id);

        return $this->createRedirectResponse('/listUsers');
    }

    /**
     * Displays all users from database in a paginated, filtered and sorted manner.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getUsers(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $userSearchResult = $this->userService->getUsers($criteria);
        $paginator = new Paginator($userSearchResult->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-users-listing.phtml',
            ['users' => $userSearchResult->getItems(), 'paginator' => $paginator, 'order' => $requestAttributes['order']]);
    }

    /**
     * Displays the "Add User" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addNewUser(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-user-add.phtml', $requestAttributes);
    }

    /**
     * Displays the "Edit User" page containing the form pre-filled with the selected user's information.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function editUser(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $user = $this->userService->getUser($id);

        return $this->renderer->renderView('admin-user-edit.phtml', ['user' => $user]);
    }
}
