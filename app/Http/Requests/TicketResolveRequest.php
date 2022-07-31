<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketResolveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'],
        ];
    }
}
