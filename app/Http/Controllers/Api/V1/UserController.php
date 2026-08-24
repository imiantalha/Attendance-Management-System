<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list');
    }

    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::with('roles')
                ->latest()
                ->paginate(15)
        );
    }

    public function show(User $user): UserResource
    {
        $user->load('roles');

        return new UserResource($user);
    }
}
