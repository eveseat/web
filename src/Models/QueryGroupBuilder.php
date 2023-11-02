<?php

namespace Seat\Web\Models;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class QueryGroupBuilder
{
    protected bool $is_and_group;
    protected Builder $query;

    public function __construct(Builder $query, bool $is_and_group){
        $this->query = $query;
        $this->is_and_group = $is_and_group;
    }

    public function isAndGroup(): bool {
        return $this->is_and_group;
    }

    public function where(Closure $callback): QueryGroupBuilder {
        if($this->is_and_group){
            $this->query->where($callback);
        } else {
           $this->query->orWhere($callback);
        }
        return $this;
    }

    public function whereHas(string $relation, Closure $callback): QueryGroupBuilder {
        if($this->is_and_group){
            $this->query->whereHas($relation, $callback);
        } else {
            $this->query->orWhereHas($relation, $callback);
        }
        return $this;
    }
}