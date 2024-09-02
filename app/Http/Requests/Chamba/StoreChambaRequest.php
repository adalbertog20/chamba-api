<?php

namespace App\Http\Requests\Chamba;

use Illuminate\Foundation\Http\FormRequest;

class StoreChambaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'job_id' => ['required', 'string', 'exists:jobs,id'],
            'worker_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}