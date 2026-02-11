<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'   => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['draft', 'in_progress', 'done'])],
            'bobot'  => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'  => 'Status harus salah satu dari: draft, in_progress, done.',
            'bobot.min'  => 'Bobot task minimal 1.',
        ];
    }
}