<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @throws Throwable
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('TolkoRENT');
        });
    }
}
