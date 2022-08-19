<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLobbyRequest extends FormRequest
{
    /** @return string[] */
    public function attributes(): array
    {
        return [
            'member_name' => 'name',
        ];
    }

    /** @return array<string>[] */
    public function rules(): array
    {
        return [
            'member_name' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }
}
