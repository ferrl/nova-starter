<?php

namespace App\Models\Concerns;

use App\Domain\Support\UniversalIdentifierGenerator;
use Illuminate\Database\Eloquent\Model;

trait HasUniversalIdentifiers
{
    /**
     * Generate universal identifiers on create event.
     *
     * @return void
     */
    public static function bootHasUniversalIdentifiers()
    {
        static::creating(function (Model $model) {
            $model->{$model->getKeyName()} = (new UniversalIdentifierGenerator)->generate();
        });
    }
}
