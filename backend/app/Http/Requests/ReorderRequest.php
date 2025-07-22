<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ResponseTrait;

class ReorderRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'ordered_task_ids' => 'required|array',
            'ordered_task_ids.*' => 'integer|exists:tasks,id',
        ];
    }

    public function messages()
    {
        return [
            'ordered_task_ids.required' => 'The ordered_task_ids field is required.',
            'ordered_task_ids.array' => 'The ordered_task_ids must be an array.',
            'ordered_task_ids.*.integer' => 'Each task ID must be an integer.',
            'ordered_task_ids.*.exists' => 'One or more task IDs do not exist.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 422));
    }
}
