<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transforms the descriptions array into an associative array keyed by 'lang'.
     *
     * @param array $descriptions
     * @return array
     */
    protected function transformDescriptions($descriptions): array
    {
        $transformed = [];
        foreach ($descriptions as $description) {
            $transformed[$description['lang']] = $description;
        }
        return $transformed;
    }
}
