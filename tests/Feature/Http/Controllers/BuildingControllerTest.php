<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Building;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BuildingController
 */
class BuildingControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function indexDisplaysView()
    {
        $buildings = Building::factory(3)->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('building.index'));

        $response->assertOk();
        $response->assertViewIs('building.index');
        // $response->assertViewHas('buildings'); //because buildings comes from ajax in view
    }


    /**
     * @test
     */
    public function createDisplaysView()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('building.create'));

        $response->assertOk();
        $response->assertViewIs('building.create');
    }


    /**
     * @test
     */
    public function storeUsesFormRequestValidation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BuildingController::class,
            'store',
            \App\Http\Requests\Building\StoreRequest::class
        );
    }

    /**
     * @test
     */
    public function storeSavesAndRedirects()
    {
        $attrs = Building::factory()->definition();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('building.store'), $attrs);

        $buildings = Building::query()->where($attrs)->get();

        $this->assertCount(1, $buildings);
        $building = $buildings->first();

        $response->assertRedirect(route('building.index'));
        $response->assertSessionHas('building.id', $building->id);
    }


    /**
     * @test
     */
    public function showDisplaysView()
    {
        $building = Building::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('building.show', $building));

        $response->assertOk();
        $response->assertViewIs('building.show');
        $response->assertViewHas('building');
    }


    /**
     * @test
     */
    public function editDisplaysView()
    {
        $building = Building::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('building.edit', $building));

        $response->assertOk();
        $response->assertViewIs('building.edit');
        $response->assertViewHas('building');
    }


    /**
     * @test
     */
    public function updateUsesFormRequestValidation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BuildingController::class,
            'update',
            \App\Http\Requests\Building\UpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function updateRedirects()
    {
        $building = Building::factory()->create();
        $newAttrs = Building::factory()->definition();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->put(route('building.update', $building), $newAttrs);

        $building->refresh();

        $response->assertRedirect(route('building.index'));
        $response->assertSessionHas('building.id', $building->id);

        foreach ($newAttrs as $key => $val) {
            $this->assertEquals($val, $building->$key);
        }
    }


    /**
     * @test
     */
    public function destroyDeletesAndRedirects()
    {
        $building = Building::factory()->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete(route('building.destroy', $building));

        $response->assertRedirect(route('building.index'));

        $this->assertDeleted($building);
    }
}
