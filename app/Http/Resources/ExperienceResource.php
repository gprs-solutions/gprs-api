<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'image'        => $this->resource->image,
            'start'        => $this->resource->start,
            'end'          => $this->resource->end,
            'descriptions' => $this->transformDescriptions(DescriptionResource::collection($this->descriptions()->get())),
        ];
    }

    /**
     * Transforms the descriptions array into an associative array keyed by 'lang'.
     *
     * @param array $descriptions
     * @return array
     */
    private function transformDescriptions($descriptions): array
    {
        $transformed = [];
        foreach ($descriptions as $description) {
            $transformed[$description['lang']] = $description;
        }
        return $transformed;
    }
}
