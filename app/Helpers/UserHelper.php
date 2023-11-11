<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserHelper
{
    public static function validateUserRequest(Request $request, $userId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ];

        if ($userId) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return Validator::make($request->all(), $rules);
    }
}
