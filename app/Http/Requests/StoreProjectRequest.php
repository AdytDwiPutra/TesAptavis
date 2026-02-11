<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'       => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'             => 'Nama project wajib diisi.',
            'start_date.required'       => 'Tanggal mulai wajib diisi.',
            'end_date.required'         => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal'   => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}