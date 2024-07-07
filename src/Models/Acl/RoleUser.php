<?php

namespace Seat\Web\Models\Acl;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Seat\Web\Models\User;

/**
 * @property int role_id
 * @property int user_id
 * @property User user
 * @property Role role
 */
class RoleUser extends Pivot
{

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}