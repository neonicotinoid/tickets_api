<?php

namespace App\Http\Controllers;

use App\Actions\ResolveTicketAction;
use App\Enums\TicketStatus;
use App\Exceptions\TicketAlreadyResolvedException;
use App\Http\Requests\TicketResolveRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Jobs\SendTicketResponseEmailJob;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use TheSeer\Tokenizer\TokenCollection;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->when($request->get('status') === 'active', function (Builder $query) {
                return $query->active();
            })
            ->when($request->get('status') === 'resolved', function (Builder $query) {
                return $query->resolved();
            })
            ->when($request->get('date') === 'desc', function (Builder $query) {
                return $query->orderBy('created_at', 'DESC');
            })
            ->when($request->get('date') === 'asc', function (Builder $query) {
                return $query->orderBy('created_at', 'ASC');
            })
            ->paginate(10);

        $tickets->appends($request->query());

        return Inertia::render('Tickets', [
            'tickets' => (new TicketCollection($tickets)),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Ticket $ticket)
    {
        return response()->json(new TicketResource($ticket));
    }

    public function resolve(TicketResolveRequest $request, Ticket $ticket, ResolveTicketAction $resolve)
    {
        try {
            $resolve($ticket, $request->validated('comment'));
            $this->dispatch(new SendTicketResponseEmailJob($ticket));
        } catch(TicketAlreadyResolvedException $e) {
            return redirect()->back()->withErrors([
                'resolve' => $e->getMessage(),
            ]);
        }

        return redirect()->back();
    }

    public function edit(Ticket $ticket)
    {
        //
    }

    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    public function destroy(Ticket $ticket)
    {
        //
    }
}
