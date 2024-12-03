<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // saling terhubung dengan ArticleResource
        return [
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $this->collection->transform(function ($articles) {
                return new ArticleResource($articles);
            }),
        ];
    }
}
