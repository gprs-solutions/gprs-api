<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Description extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the parent descriptionable model.
     *
     * @return MorphTo
     */
    public function descriptionable(): MorphTo
    {
        return $this->morphTo();
    }
}
