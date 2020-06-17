<?php

namespace Glocurrency\ApiLayer\Http\Controllers\Admin;

use Illuminate\Http\Request;

class PingController
{
    public function ping(Request $request)
    {
        return response()->json([
            'success' => true,
            'codename' => config('app.name'),
        ]);
    }
}
