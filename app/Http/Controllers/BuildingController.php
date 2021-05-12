<?php

namespace App\Http\Controllers;

use App\Http\Requests\Building\StoreRequest;
use App\Http\Requests\Building\UpdateRequest;
use App\Models\Building;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return view('building.index');
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        return view('building.create');
    }

    /**
     * @param  StoreRequest  $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $building = Building::create($request->validated());

        $request->session()->flash('building.id', $building->id);

        return redirect()->route('building.index');
    }

    /**
     * @param  Request  $request
     * @param  Building  $building
     * @return Response
     */
    public function show(Request $request, Building $building)
    {
        return view('building.show', compact('building'));
    }

    /**
     * @param  Request  $request
     * @param  Building  $building
     * @return Response
     */
    public function edit(Request $request, Building $building)
    {
        return view('building.edit', compact('building'));
    }

    /**
     * @param  UpdateRequest  $request
     * @param  Building  $building
     * @return Response
     */
    public function update(UpdateRequest $request, Building $building)
    {
        $building->update($request->validated());

        $request->session()->flash('building.id', $building->id);

        return redirect()->route('building.index');
    }

    /**
     * @param  Request  $request
     * @param  Building  $building
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, Building $building)
    {
        $building->delete();

        return redirect()->route('building.index');
    }
}
