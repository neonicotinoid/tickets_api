<?php

namespace App\Http\Controllers\Api;

use App\Actions\ResolveTicketAction;
use App\Enums\TicketStatus;
use App\Exceptions\TicketAlreadyResolvedException;
use App\Filters\TicketFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketResolveRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Jobs\SendTicketResponseEmailJob;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index(Request $request, TicketFilter $filter)
    {
        $tickets = Ticket::query()
            ->filter($filter)
            ->paginate(10)
            ->appends($request->query());
        
        return response()->json([
            'tickets' => new TicketCollection(Ticket::all()),
        ]);
    }

    public function store(TicketStoreRequest $request)
    {
        $ticket = new Ticket($request->validated());
        $ticket->status = TicketStatus::Active;
        $ticket->save();

        return response()->json(['ticket' => new TicketResource($ticket)]);
    }

    public function resolve(TicketResolveRequest $request, Ticket $ticket, ResolveTicketAction $resolve)
    {
        try {
            $resolve($ticket, $request->validated('comment'));
            $this->dispatch(new SendTicketResponseEmailJob($ticket));
        } catch (TicketAlreadyResolvedException $e) {
            return response()->json([
                'errors' => ['resolve' => $e->getMessage()],
            ], 422);
        }

        return response()->json([
            'ticket' => $ticket,
        ]);
    }
}
