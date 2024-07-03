<?php

namespace Seat\Web\Models;

use Seat\Services\Models\ExtensibleModel;
use Seat\Web\Models\Acl\Role;

/**
 * @property int $id
 * @property int $role_id
 * @property int update_interval
 *
 * @property Role $role
 */
class CharacterSchedulingRule extends ExtensibleModel
{
    public $timestamps = false;
    protected $primaryKey = "role_id";

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}