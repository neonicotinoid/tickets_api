<?php

namespace App\Http\Controllers;

use App\Actions\ResolveTicketAction;
use App\Enums\TicketStatus;
use App\Exceptions\TicketAlreadyResolvedException;
use App\Filters\TicketFilter;
use App\Http\Requests\TicketResolveRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Resources\TicketCollection;
use App\Jobs\SendTicketResponseEmailJob;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request, TicketFilter $filter)
    {
        $tickets = Ticket::query()
            ->filter($filter)
            ->paginate(10)
            ->appends($request->query());

        return Inertia::render('Tickets', [
            'tickets' => (new TicketCollection($tickets)),
        ]);
    }

    public function store(TicketStoreRequest $request)
    {
        $ticket = new Ticket($request->validated());
        $ticket->status = TicketStatus::Active;
        $ticket->save();

        return redirect()->back()->with('success', 'Ваша заявка успешно отправлена');
    }

    public function resolve(TicketResolveRequest $request, Ticket $ticket, ResolveTicketAction $resolve)
    {
        try {
            $resolve($ticket, $request->validated('comment'));
            $this->dispatch(new SendTicketResponseEmailJob($ticket));
        } catch (TicketAlreadyResolvedException $e) {
            return redirect()->back()->withErrors([
                'resolve' => $e->getMessage(),
            ]);
        }

        return redirect()->back();
    }
}
