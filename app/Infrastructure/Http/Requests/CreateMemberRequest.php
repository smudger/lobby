<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            /** @phpstan-ignore-next-line  */
            'lobby_id' => is_string($this->lobby_id) ? strtoupper($this->lobby_id) : $this->lobby_id,
        ]);
    }

    /** @return string[] */
    public function attributes(): array
    {
        return [
            'lobby_id' => 'lobby code',
        ];
    }

    /** @return array<string>[] */
    public function rules(): array
    {
        return [
            'lobby_id' => [
                'required',
                'string',
                'size:4',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'socket_id' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
