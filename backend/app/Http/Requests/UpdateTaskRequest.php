<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
    use ResponseTrait;
    public function authorize(): bool
    {
        // Only authenticated users can update tasks
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
            'priority' => 'sometimes|required|in:low,medium,high',
            'order' => 'sometimes|nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required when present.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'description.string' => 'The description must be a valid string.',

            'status.required' => 'The task status is required when present.',
            'status.in' => 'The status must be either pending or completed.',

            'priority.required' => 'The task priority is required when present.',
            'priority.in' => 'The priority must be one of the following: low, medium, or high.',

            'order.integer' => 'The order must be a number.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 422));
    }
}
