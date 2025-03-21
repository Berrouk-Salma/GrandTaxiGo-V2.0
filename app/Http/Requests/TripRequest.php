<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'origin_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
        ];
    }
}