<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ResponseTrait;

class LoginRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.exists' => 'Username does not exist.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 422));
    }
}
