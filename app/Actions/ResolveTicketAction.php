<?php

namespace App\Actions;

use App\Enums\TicketStatus;
use App\Exceptions\TicketAlreadyResolvedException;
use App\Models\Ticket;

class ResolveTicketAction
{

    /**
     * @throws TicketAlreadyResolvedException
     */
    public function __invoke(Ticket $ticket, string $comment): Ticket
    {
        if ($ticket->status === TicketStatus::Resolved) {
            throw new TicketAlreadyResolvedException('Ticket (id: ' . $ticket->id . ') you trying to resolve already resolved');
        }
        $ticket->comment = $comment;
        $ticket->status = TicketStatus::Resolved;
        $ticket->save();

        return $ticket;
    }

}
