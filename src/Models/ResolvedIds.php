<?php

namespace Seat\Web\Models;

use Illuminate\Database\Eloquent\Model;

class ResolvedIds extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name'];
}
