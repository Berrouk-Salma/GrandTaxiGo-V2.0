<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isDriver();
    }

    public function rules()
    {
        return [
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'current_location' => 'required|string|max:255',
            'status' => 'required|in:available,unavailable',
        ];
    }
}