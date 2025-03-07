<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user),
            ],
            'phone' => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->user),
            ],
            'user_type' => 'required|in:passenger,driver',
            'role' => 'required|in:user,admin',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['profile_image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
            $rules['profile_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}