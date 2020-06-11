<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Service\Pagination\PaginationService;
use App\Service\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @var PaginationService
     */
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        parent::__construct();
        $this->paginationService = $paginationService;
    }

    public function index(Request $request): View
    {
        $email = ($request->get('email')) ?? $request->get('email');
        $status = ($request->get('status')) ?? $request->get('status');
        $role = ($request->get('role')) ?? $request->get('role');
        $page = ($request->get('page')) ? $request->get('page') : 1;

        $response = Http::withToken($this->getToken())->get(
            $this->apiHost . '/users' . '?email=' . $email . '&status=' . $status . '&role=' . $role . '&page=' . $page
        );
        if ($response->clientError()) {
            abort($response->status(), $response->object()->message);
        } else {
            $paginationArray = $this->paginationService->build($response->json()['totalUsers']);
            return view('admin.users.index',
                [
                    'users' => json_decode($response->json()['users']),
                    'statusList' => UserService::statusList(),
                    'rolesList' => UserService::rolesList(),
                    'paginationArray' => $paginationArray
                ]
            );
        }
    }

    public function show($id): View
    {
        $response = Http::withToken($this->getToken())->get(
            $this->apiHost . '/users/' . $id
        );

        if ($response->clientError()) {
            abort($response->status(), $response->object()->error);
        } else {
            return view('admin.users.show', ['user' => json_decode($response['user'])]);
        }
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'statusList' => UserService::statusList(),
            'rolesList' => UserService::rolesList(),
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $response = Http::withToken($this->getToken())->asForm()->post($this->apiHost . '/users', [
            'email' => $request['email'],
            'password' => $request['password'],
            'role' => $request['role'],
            'status' => $request['status']
        ]);

        if (!$response->successful()) {
            return redirect()->route('admin.users.create')->with('error', $response->object()->error);
        } else {
            return redirect()->route('admin.users.index')->with('success', $response['message']);
        }
    }

    public function edit($id)
    {
        $response = Http::withToken($this->getToken())->get(
            $this->apiHost . '/users/' . $id
        );

        if ($response->clientError()) {
            abort($response->status(), $response->object()->error);
        } else {
            return view('admin.users.edit', [
                'user' => json_decode($response['user']),
                'statusList' => UserService::statusList(),
                'rolesList' => UserService::rolesList(),
            ]);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        $response = Http::withToken($this->getToken())->asForm()->put($this->apiHost . '/users/' . $id, [
            'email' => $request['email'],
            'password' => ($request['password']) ? $request['password'] : '',
            'role' => $request['role'],
            'status' => $request['status']
        ]);

        if (!$response->successful()) {
            return redirect()->route('admin.users.edit', $id)->with('error', $response->object()->error);
        } else {
            return redirect()->route('admin.users.show', $id)->with('success', $response['message']);
        }
    }

    public function activate($id): RedirectResponse
    {
        $response = Http::withToken($this->getToken())->get(
            $this->apiHost . '/users/' . $id . '/activate'
        );
        if ($response->clientError()) {
            abort($response->status(), $response->object()->message);
        } else {
            return redirect()->route('admin.users.show', $id)->with('success', $response['message']);
        }
    }

    public function block($id): RedirectResponse
    {
        $response = Http::withToken($this->getToken())->get(
            $this->apiHost . '/users/' . $id . '/block'
        );
        if ($response->clientError()) {
            abort($response->status(), $response->object()->message);
        } else {
            return redirect()->route('admin.users.show', $id)->with('success', $response['message']);
        }
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete(
            $this->apiHost . '/users/' . $id
        );
        if ($response->clientError()) {
            abort($response->status(), $response->object()->message);
        } else {
            return redirect()->route('admin.users.index')->with('success', $response['message']);
        }
    }

}
