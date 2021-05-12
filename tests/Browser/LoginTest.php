<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws Throwable
     */
    public function testFirst()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $browser->loginAs($user)
                ->visit(route('home'))
                ->assertSee('Dashboard')
            ;
        });
    }
}
