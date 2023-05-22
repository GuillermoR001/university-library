<?php

namespace App\Http\Controllers\API\Checkouts;

use App\Enums\CodeResponses;
use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Checkout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Http\Resources\Checkout\CheckoutResource;
use App\Http\Requests\Checkout\CheckoutForStudenRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CheckoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index() : JsonResponse | AnonymousResourceCollection
    {
        try {
            $checkouts = Checkout::all();
            return CheckoutResource::collection($checkouts);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * List student checkout
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function studentCheckouts() : JsonResponse | AnonymousResourceCollection
    {
        try {
            $user = auth('api')->user();
            $checkouts = Checkout::where('user_id', $user->id)
                                ->get();
            return CheckoutResource::collection($checkouts);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create checkout from librarian role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(CheckoutRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($request->user_id);
            $book = Book::findOrFail($request->book_id);
            if ($book->stock == 0) {
                abort(Response::HTTP_BAD_REQUEST, 'Book out of stock');
            }
            $checkout = Checkout::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'checkout_date' => Carbon::now()->format('Y-m-d h:i:s'),
                'comments' => $request->comments
            ]);
            $book->stock -= 1;
            $book->save();
            DB::commit();
            return response()->json([
                'message' => 'Checkout created.',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $checkout,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->serverErrorMessage,
                'response_code' => CodeResponses::FAIL->value,
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create checkout from student session
     *
     * @param CheckoutRequest $request
     * @return JsonResponse
     */
    public function addCheckout(CheckoutForStudenRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth('api')->user();
            $book = Book::findOrFail($request->book_id);
            if ($book->stock == 0) {
                abort(Response::HTTP_BAD_REQUEST, 'Book out of stock');
            }
            $checkout = Checkout::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'comments' => null,
                'checkout_date' => Carbon::now()->format('Y-m-d h:i:s')
            ]);
            $book->stock -= 1;
            $book->save();
            DB::commit();
            return response()->json([
                'message' => 'Checkout added.',
                'response_code' => CodeResponses::SUCCESS->value,
                'data' => $checkout,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Db::rollBack();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        try {
            $user = auth('api')->user();
            if ($user->user_rol->value == UserRoles::STUDENT->value && $checkout->user_id != $user->id) {
                abort(Response::HTTP_UNAUTHORIZED, 'Action denied.');
            }
            return new CheckoutResource($checkout);
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $checkout->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Checkout created.',
                'response_code' => CodeResponses::SUCCESS->value,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Db::rollBack();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Complete checkout
     *
     * @param Checkout $checkout
     * @return JsonResponse
     */
    public function markAsReturned(Checkout $checkout) : JsonResponse
    {
        try {
            DB::beginTransaction();
            if ($checkout->return_date != null) {
                abort(Response::HTTP_BAD_REQUEST, 'Already marked as returned.');
            }

            $checkout->update([
                'return_date' => Carbon::now()->format('Y-m-d h:i:s')
            ]);

            $checkout->book->stock += 1;
            $checkout->book->save();
            DB::commit();
            return response()->json([
                'message' => 'Checkout completed.',
                'response_code' => CodeResponses::SUCCESS->value,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $this->serverErrorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $genre)
    {
        //
    }
}
