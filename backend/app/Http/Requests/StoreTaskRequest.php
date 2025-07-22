<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
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
}
