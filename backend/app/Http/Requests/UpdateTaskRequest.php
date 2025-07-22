<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
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
}
