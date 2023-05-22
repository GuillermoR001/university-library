<?php

namespace App\Http\Controllers\API\Genres;

use App\Enums\CodeResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\GenreRequest;
use App\Http\Resources\Genre\GenreResource;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse | \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $genres = Genre::all();
            return GenreResource::collection($genres);
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GenreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenreRequest $request) : JsonResponse
    {
        try {
            $validated = $request->validated();

            $genre = Genre::create($validated);

            return response()->json([
                'message' => 'Genre created',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $genre,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return JsonResponse|GenreResource
     */
    public function show(Genre $genre) : JsonResponse | GenreResource
    {
        try {

            return new GenreResource($genre);

        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GenreRequest $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        try {
            $validated = $request->validated();
    
            $genre->update($validated);
    
            return response()->json([
                'message' => 'User updated',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $genre,
            ]);
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

}
