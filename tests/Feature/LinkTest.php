<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_link()
    {
        $this->freezeTime();
        $response = $this->post(route('store'), [
            'origin' => 'http://example.com',
            'expiration' => 100,
            'max_follows' => 0
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('links', [
            'origin' => 'http://example.com',
            'expired_at' => Carbon::now()->addMinutes(100),
            'max_follows' => null
        ]);
    }

    public function test_link_following()
    {
        $this->freezeTime();

        $this->post(route('store'), [
            'origin' => 'http://example.com',
            'expiration' => 100,
            'max_follows' => 1
        ]);

        $shortcut = DB::table('links')
            ->select('shortcut')
            ->where([
                'origin' => 'http://example.com',
                'expired_at' => Carbon::now()->addMinutes(100),
                'max_follows' => 1
            ])
            ->value('shortcut');

        $response = $this->get(route('show', ['shortcut' => $shortcut]));

        $response->assertRedirect('http://example.com');

        $this->assertDatabaseHas('links', [
            'origin' => 'http://example.com',
            'expired_at' => Carbon::now()->addMinutes(100),
            'max_follows' => 0
        ]);
    }

    public function test_link_expiration()
    {
        $this->freezeTime();

        $this->post(route('store'), [
            'origin' => 'http://example.com',
            'expiration' => 10,
            'max_follows' => 1
        ]);

        $shortcut = DB::table('links')
            ->select('shortcut')
            ->where([
                'origin' => 'http://example.com',
                'expired_at' => Carbon::now()->addMinutes(10),
                'max_follows' => 1
            ])
            ->value('shortcut');

        $this->travelTo(Carbon::now()->addMinutes(11));

        $response = $this->get(route('show', ['shortcut' => $shortcut]));

        $response->assertNotFound();
    }

    public function test_link_limit_following()
    {
        $this->freezeTime();

        $this->post(route('store'), [
            'origin' => 'http://example.com',
            'expiration' => 10,
            'max_follows' => 1
        ]);

        $shortcut = DB::table('links')
            ->select('shortcut')
            ->where([
                'origin' => 'http://example.com',
                'expired_at' => Carbon::now()->addMinutes(10),
                'max_follows' => 1
            ])
            ->value('shortcut');

        $response = $this->get(route('show', ['shortcut' => $shortcut]));

        $response->assertRedirect('http://example.com');

        $response = $this->get(route('show', ['shortcut' => $shortcut]));

        $response->assertNotFound();
    }
}
