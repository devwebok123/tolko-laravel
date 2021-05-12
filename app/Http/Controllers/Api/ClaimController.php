<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\StoreRequest;
use App\Http\Resources\Claim\ClaimResource;
use App\Services\Models\ClaimService;

class ClaimController extends Controller
{

    public function store(StoreRequest $request, ClaimService $service)
    {
        return response()->json(ClaimResource::make($service->store($request->getPhone(), $request->referer())));
    }
}
