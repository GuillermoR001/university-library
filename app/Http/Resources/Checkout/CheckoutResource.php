<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'book' => $this->book,
            'book_genre' => $this->book->genre,
            'comments' => $this->comments,
            'checkout_date' => $this->checkout_date,
            'return_date' => $this->return_date,
        ];
    }
}
