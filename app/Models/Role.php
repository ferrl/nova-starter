<?php

namespace App\Models;

use App\Models\Concerns\HasUniversalIdentifiers;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
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

    /**
     * Is root role?
     *
     * @return bool
     * @throws \Exception
     */
    public function isRootRole()
    {
        return Role::query()->orderBy('created_at')->first()->getKey() === $this->getKey();
    }
}
