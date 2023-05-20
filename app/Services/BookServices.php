<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookServices
{
    /**
     * Filter books
     *
     * @param string $search
     * @param integer $genreId
     * @return Collection
     */
    public function search(string $search, int $genreId) : Collection
    {
        $genre = Genre::find($genreId);

        if (!$genre) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Genre not found by genre_id',
                    'status' => Response::HTTP_NOT_FOUND,
                ]), Response::HTTP_NOT_FOUND
            );
        }

        return Book::where('title', 'LIKE', "%{$search}%")
            ->orWhere(function ($query) use ($search, $genreId) {
                $query->where('author', 'LIKE', "%{$search}%")
                    ->orWhere('genre_id', $genreId);
            })->get();
    }
}

