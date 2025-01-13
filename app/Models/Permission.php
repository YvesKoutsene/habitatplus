<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
