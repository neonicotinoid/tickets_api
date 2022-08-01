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

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Request API",
 *      description="Request API"
 * )
 *
 */
class TicketController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/requests",
     *     tags={"Requests"},
     *     description="Full requests list",
     *     summary="Return requests list with pagination",
     *     @OA\Response(response="default", description="Requests list", @OA\JsonContent())
     * )
     */
    public function index(Request $request, TicketFilter $filter)
    {
        $tickets = Ticket::query()
            ->filter($filter)
            ->paginate(10)
            ->appends($request->query());

        return new TicketCollection($tickets);
    }
    /**
     * @OA\Post(
     *      path="api/requests/",
     *      operationId="storeRequest",
     *      tags={"Requests"},
     *      summary="Store new request",
     *      @OA\RequestBody(
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent()
     *      )
     * )
     */

    public function store(TicketStoreRequest $request)
    {
        $ticket = new Ticket($request->validated());
        $ticket->status = TicketStatus::Active;
        $ticket->save();

        return (new TicketResource($ticket))->response();
    }

    /**
     * @OA\Put(
     *      path="api/requests/",
     *      operationId="resolveRequest",
     *      tags={"Requests"},
     *      summary="Resolve active request with moderator's comment",
     *      @OA\RequestBody(
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     * )
     */
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
