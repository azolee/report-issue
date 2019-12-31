<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request);
        return new UserResource($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = $this->userService->update($request, $user);
        return new UserResource($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $this->userService->delete($user);
        return response()->json(null, 204);
    }

    /**
     * Display the logged in resource.
     *
     * @param  \App\User
     * @return \App\Http\Resources\UserResource
     */
    public function self(Request $request)
    {
        return new UserResource($request->user(), 200);
    }

}
