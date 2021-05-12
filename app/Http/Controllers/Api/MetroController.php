<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Metro\MetroCollection;
use App\Models\Metro;

class MetroController extends Controller
{
    /**
     * @param  Request  $request
     * @return MetroCollection
     */
    public function getMetroName(Request $request)
    {
        $names = Metro::query()
            ->select('name')
            ->where('name', 'LIKE', '%'.$request->input('metros_name').'%')
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();

        return new MetroCollection($names);
    }
}
