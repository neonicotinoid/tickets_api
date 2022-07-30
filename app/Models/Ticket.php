<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id;
 * @property string name;
 * @property string email;
 * @property TicketStatus status;
 * @property string message;
 * @property string comment;
 */
class Ticket extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => TicketStatus::class,
    ];
}
