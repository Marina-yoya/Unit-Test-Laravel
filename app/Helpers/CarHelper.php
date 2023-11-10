<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class CarHelper
{
    public static function validateCarRequest(Request $request)
    {
        return validator($request->all(), [
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|integer',
        ]);
    }
}
