<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Service\Pagination\PaginationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
{
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        parent::__construct();
        $this->paginationService = $paginationService;
    }

    public function index(Request $request): View
    {
        $uriString = $this->paginationService->prepareUriString();
        $page = $this->paginationService->getPage();

        $email = ($request->get('email')) ?? $request->get('email');
        $status = ($request->get('status')) ?? $request->get('status');
        $role = ($request->get('role')) ?? $request->get('role');

        $response = Http::get($this->apiHost . '/users' . '?email=' . $email . '&status=' . $status . '&role=' . $role . '&page=' . $page);
        $users = json_decode($response->json()['users']);
        $statusList = $response->json()['statusList'];
        $rolesList = $response->json()['rolesList'];
        $totalUsers = $response->json()['totalUsers'];

        return view('admin.user.index',
            [
                'users' => $users,
                'statusList' => $statusList,
                'rolesList' => $rolesList,
                'url' => $uriString,
                'page' => $page,
                'totalUsers' => $totalUsers
            ]
        );
    }
}
