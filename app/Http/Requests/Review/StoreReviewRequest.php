<?php

namespace App\Http\Requests\Review;

use App\Models\RequestChamba;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'request_chamba_id' => 'required|exists:request_chambas,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function($validator) {
            $requestChamba = RequestChamba::find($this->request_chamba_id);
            if($requestChamba && $requestChamba->status !== 'done') {
                $validator->errors()->add('request_chamba_id', 'Request Chamba must be done to review');
            }
        });
    }
}
