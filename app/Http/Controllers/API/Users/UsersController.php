<?php

namespace App\Http\Controllers\API\Users;

use App\Enums\CodeResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|UserResource
     */
    public function index() : JsonResponse | \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $users = User::all();

            return UserResource::collection($users);

        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(UserRequest $request) : JsonResponse
    {
        try {
            $validated = $request->validated();

            $password = str::random(8);
    
            $user = User::create(array_merge($validated, [
                'password' => bcrypt($password)
            ]));
    
            return response()->json([
                'message' => 'User created',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $user,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return JsonResponse|UserResource
     */
    public function show(User $user) : JsonResponse | UserResource
    {
        try {

            return new UserResource($user);

        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user) : JsonResponse
    {
        try {
            $validated = $request->validated();
    
            $user->update($validated);
    
            return response()->json([
                'message' => 'User updated',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

}
