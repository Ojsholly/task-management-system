<?php

namespace App\Http\Resources\Task;

use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskResourceCollection extends ResourceCollection
{
    use PaginationTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'meta' => $this->metaData(),
            'tasks' => parent::toArray($request),
        ];
    }
}
