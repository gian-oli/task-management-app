<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreTaskRequest extends FormRequest
{
    use ResponseTrait;
    public function authorize(): bool
    {
        // Allow only authenticated users to create tasks
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'priority' => 'required|in:low,medium,high',
            // 'order' is optional but if present should be integer
            'order' => 'nullable|integer',
            // user_id should not be passed in request, as it's set from auth
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'description.string' => 'The description must be a valid string.',

            'status.required' => 'The task status is required.',
            'status.in' => 'The status must be either pending or completed.',

            'priority.required' => 'The task priority is required.',
            'priority.in' => 'The priority must be one of the following: low, medium, or high.',

            'order.integer' => 'The order must be an integer.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 422));
    }
}
