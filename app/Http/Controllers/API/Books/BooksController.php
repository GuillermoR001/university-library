<?php

namespace App\Http\Controllers\API\Books;

use App\Enums\CodeResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Http\Requests\Book\FilterRequest;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use App\Services\BookServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BooksController extends Controller
{
    private readonly BookServices $bookServices;

    public function __construct(BookServices $bookServices)
    {
        $this->bookServices = $bookServices;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index() : JsonResponse | AnonymousResourceCollection
    {
        try {
            $books = Book::all();
            return BookResource::collection($books);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BookRequest  $request
     * @return JsonResponse
     */
    public function store(BookRequest $request) : JsonResponse
    {
        try {
            $validated = $request->validated();

            $book = Book::create($validated);

            return response()->json([
                'message' => 'Book created',
                'response_code' => CodeResponses::SUCCESS->value,
                'book' => $book,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'book' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        try {

            return new BookResource($book);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'book' => null,
            ]);
        }
    }

    /**
     * Filter books by title, author or genre
     *
     * @param FilterRequest $request
     * @return AnonymousResourceCollection
     */
    public function filter(FilterRequest $request) : AnonymousResourceCollection
    {
        $search = $request->search;
        $genreId = $request->genre_id;
        $books = $this->bookServices->search($search, $genreId);

        return BookResource::collection($books);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        try {
            $validated = $request->validated();

            $book->update($validated);

            return response()->json([
                'message' => 'Book updated',
                'response_code' => CodeResponses::SUCCESS->value,
                'book' => $book,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'book' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $genre)
    {
        //
    }
}
