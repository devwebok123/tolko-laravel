<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Block;
use App\Models\Traits\NumberFormatter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BlockController
 */
class BlockControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker, NumberFormatter;

    /**
     * @test
     */
    public function indexDisplaysView()
    {
        $user = User::factory()->create();
        Block::factory()->count(3)->create();
        $response = $this->actingAs($user)->get(route('block.index'));

        $response->assertOk();
        $response->assertViewIs('block.index');
    }


    /**
     * @test
     */
    public function createDisplaysView()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('block.create'));

        $response->assertOk();
        $response->assertViewIs('block.create');
    }


    /**
     * @test
     */
    public function storeUsesFormRequestValidation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BlockController::class,
            'store',
            \App\Http\Requests\Block\BlockStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function storeSavesAndRedirects()
    {
        $user = User::factory()->create();
        $attrs = Block::factory()->definition();

        $response = $this->actingAs($user)->post(route('block.store'), $attrs);

        $attrs['filling'] = implode(',', $attrs['filling']);
        $attrs['shower_bath'] = implode(',', $attrs['shower_bath']);
        $attrs['living_conds'] = implode(',', $attrs['living_conds']);
        $attrs['included'] = implode(',', $attrs['included']);

        $blocks = Block::query()->where($attrs)->get();

        $this->assertCount(1, $blocks);
        $block = $blocks->first();

        $response->assertRedirect(route('block.show', ['block' => $block->id]));
        $response->assertSessionHas('block.id', $block->id);
    }


    /**
     * @test
     */
    public function showDisplaysView()
    {
        $block = Block::factory()->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('block.show', $block));

        $response->assertOk();
        $response->assertViewIs('block.show');
        $response->assertViewHas('block');
    }


    /**
     * @test
     */
    public function editDisplaysView()
    {
        $block = Block::factory()->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('block.edit', $block));

        $response->assertOk();
        $response->assertViewIs('block.edit');
        $response->assertViewHas('block');
    }


    /**
     * @test
     */
    public function updateUsesFormRequestValidation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BlockController::class,
            'update',
            \App\Http\Requests\Block\BlockUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function updateRedirects()
    {
        $block = Block::factory()->create();
        $newAttrs = Block::factory()->definition();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->put(route('block.update', $block), $newAttrs);

        $block->refresh();

        $response->assertRedirect(route('block.show', ['block' => $block->id]));
        $response->assertSessionHas('block.id', $block->id);

        $formattedColumns = [
            'area',
            'kitchen_area',
            'living_area',
        ];

        foreach ($newAttrs as $key => $val) {
            $oldVal = $block->$key;
            // I dunno how to assert equal arrays, because there is second foreach
            if (is_array($val) && is_array($oldVal)) {
                foreach ($val as $k => $v) {
                    // $this->assertEquals($v, $oldVal[$k]); // todo arrays is not asserts
                }
            } else {
                if (in_array($key, $formattedColumns, true)) {
                    $val = $this->round($val);
                }
                $this->assertEquals($val, $oldVal);
            }
        }
    }


    /**
     * @test
     */
    public function destroyDeletesAndRedirects()
    {
        $block = Block::factory()->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete(route('block.destroy', $block));

        $response->assertRedirect(route('block.index'));

        $this->assertSoftDeleted($block);
    }
}
