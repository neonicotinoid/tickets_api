<?php

namespace Tests\Feature;

use App\Actions\ResolveTicketAction;
use App\Jobs\SendTicketResponseEmailJob;
use App\Mail\SendTicketResponse;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketMailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_pushes_job_for_email()
    {
        \Illuminate\Support\Facades\Queue::fake();
        Sanctum::actingAs(User::factory()->create());
        $ticket = Ticket::factory()->create();

        $this->putJson(route('api.requests.resolve', ['ticket' => $ticket->id]), [
            'comment' => 'This is moderator comment',
        ])->assertStatus(200);

        Queue::assertPushed(SendTicketResponseEmailJob::class);
    }

    public function test_it_send_mail()
    {
        Mail::fake();
        $ticket = Ticket::factory()->create();
        (new ResolveTicketAction())($ticket, 'Moderator comment');
        $job = new SendTicketResponseEmailJob($ticket);
        $job->handle();

        Mail::assertSent(SendTicketResponse::class);
    }
}
