<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Region\RegionCollection;
use App\Models\Region;

class RegionController extends Controller
{
    /**
     * @param  Request  $request
     * @return RegionCollection
     */
    public function getRegionName(Request $request)
    {
        $names = Region::query()
            ->select('name')
            ->where('name', 'LIKE', '%'.$request->input('regions_name').'%')
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();

        return new RegionCollection($names);
    }
}
