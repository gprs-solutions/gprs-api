<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'id'           => $this->resource->id,
            'link'         => $this->resource->link,
            'image'        => $this->resource->image,
            'start'        => $this->resource->start,
            'end'          => $this->resource->end,
            'descriptions' => DescriptionResource::collection($this->descriptions()->get()),
        ];
    }
}
