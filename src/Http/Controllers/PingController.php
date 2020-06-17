<?php

namespace Glocurrency\ApiLayer\Http\Controllers;

use Illuminate\Http\Request;

class PingController
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'codename' => config('app.name'),
        ]);
    }
}
