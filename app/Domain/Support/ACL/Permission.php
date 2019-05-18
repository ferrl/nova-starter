<?php

namespace App\Domain\Support\ACL;

class Permission
{
    /**
     * Permission code in database.
     *
     * @var string
     */
    public $name;

    /**
     * Display name for permission.
     *
     * @var string
     */
    public $displayName;

    /**
     * Associate to a group of permissions.
     *
     * @var string
     */
    public $group;

    /**
     * Permission constructor.
     *
     * @param string $name
     * @param string $displayName
     * @param string $group
     */
    public function __construct($name = null, $displayName = null, $group = null)
    {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->group = $group;
    }
}
