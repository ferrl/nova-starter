<?php

namespace App\Models;

use App\Models\Concerns\HasUniversalIdentifiers;
use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{
    use HasUniversalIdentifiers;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
