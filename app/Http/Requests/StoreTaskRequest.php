<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'      => ['required', 'string', 'max:255'],
            'bobot'     => ['required', 'integer', 'min:1'],
            'parent_id' => [
                'nullable',
                'integer',
                // Parent task harus ada dan berada di project yang sama
                Rule::exists('tasks', 'id')->where(function ($query) {
                    $query->where('project_id', $this->route('project'));
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'  => 'Nama task wajib diisi.',
            'bobot.required' => 'Bobot task wajib diisi.',
            'bobot.min'      => 'Bobot task minimal 1.',
            'parent_id.exists' => 'Task parent tidak ditemukan dalam project ini.',
        ];
    }
}