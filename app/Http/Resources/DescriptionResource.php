<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The request with the information.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource->id,
            'lang'        => $this->resource->lang,
            'description' => $this->resource->description,
            'name'        => $this->resource->name,
            'type'        => $this->resource->type,
        ];
    }
}
