<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Defining relations with description table.
     *
     * @return MorphMany
     */
    public function descriptions(): MorphMany
    {
        return $this->morphMany(Description::class, 'descriptionable');
    }

    /**
     * Deletes descriptions on cascade.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($exp) {
                $exp->descriptions()->delete();
            }
        );
    }
}
