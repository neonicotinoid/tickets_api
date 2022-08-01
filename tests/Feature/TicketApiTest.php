<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guest_store_ticket()
    {
        $this->postJson(route('api.requests.store'), [
            'name' => 'name',
            'email' => 'email@example.com',
            'message' => 'my message',
        ])
            ->assertStatus(200);

        $this->assertDatabaseHas('tickets', [
            'name' => 'name',
            'email' => 'email@example.com',
            'message' => 'my message',
            'status' => TicketStatus::Active->value,
        ]);
    }

    public function test_guest_cannot_resolve_ticket()
    {
        $ticket = Ticket::factory()->create();
        $this->putJson(route('api.requests.resolve', ['ticket' => $ticket->id]), [
            'comment' => 'This is moderator comment',
        ])->assertUnauthorized();
    }

    public function test_user_can_resolve_ticket()
    {
        Sanctum::actingAs(User::factory()->create());
        $ticket = Ticket::factory()->create();

        $this->putJson(route('api.requests.resolve', ['ticket' => $ticket->id]), [
            'comment' => 'This is moderator comment',
        ])->assertStatus(200);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'name' => $ticket->name,
            'email' => $ticket->email,
            'message' => $ticket->message,
            'status' => TicketStatus::Resolved->value,
            'comment' => 'This is moderator comment',
        ]);
    }

    public function test_it_cannot_resolve_already_resolved_ticket()
    {
        Sanctum::actingAs(User::factory()->create());
        $ticket = Ticket::factory()->resolved()->create();

        $this->putJson(route('api.requests.resolve', ['ticket' => $ticket->id]), [
            'comment' => 'This is moderator comment',
        ])->assertStatus(422);
    }

    public function test_it_sorts_tickets_by_status()
    {
        Sanctum::actingAs(User::factory()->create());
        $resolved = Ticket::factory(['comment' => 'Завершенное обращение'])
            ->resolved()
            ->create();
        $active = Ticket::factory(['message' => 'Актуальное обращение'])
            ->create();

        $this->getJson(route('api.requests.index', ['status' => 'active']))
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $active->id)
            ->assertJsonMissingPath('data.1.id');

        $this->getJson(route('api.requests.index', ['status' => 'resolved']))
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $resolved->id)
            ->assertJsonMissingPath('data.1.id');
    }

    public function test_it_sorts_by_created_at()
    {
        Sanctum::actingAs(User::factory()->create());
        Ticket::factory()
            ->count(2)
            ->sequence(
                ['message' => 'Старое обращение', 'created_at' => now()->subDays(2)],
                ['message' => 'Новое обращение', 'created_at' => now()]
            )->create();

        $this->getJson(route('api.requests.index'))
            ->assertStatus(200)
            ->assertJsonPath('data.0.message', 'Новое обращение')
            ->assertJsonPath('data.1.message', 'Старое обращение');

        $this->getJson(route('api.requests.index', ['date' => 'asc']))
            ->assertStatus(200)
            ->assertJsonPath('data.0.message', 'Старое обращение')
            ->assertJsonPath('data.1.message', 'Новое обращение');
    }
}
