<?php

namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketFilter
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->when($this->request->get('status') === 'active', function (Builder $query) {
            return $query->active();
        })
        ->when($this->request->get('status') === 'resolved', function (Builder $query) {
            return $query->resolved();
        })
        ->when($this->request->get('date') === 'desc', function (Builder $query) {
            return $query->orderBy('created_at', 'DESC');
        })
        ->when($this->request->get('date') === 'asc', function (Builder $query) {
            return $query->orderBy('created_at', 'ASC');
        });
    }

}
