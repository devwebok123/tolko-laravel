<?php


namespace App\Http\Controllers;

use App\Http\Requests\Setting\UpdateRequest;
use App\Services\Models\SettingService;

class SettingController extends Controller
{
    public function edit(SettingService $service)
    {
        return view('setting.edit', ['setting' => $service->getObject()]);
    }

    public function update(UpdateRequest $request, SettingService $service)
    {
        $service->update($request);

        return redirect(route('setting.edit'))->with('is_update', true);
    }
}
