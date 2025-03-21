<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculateAreaTest extends TestCase
{
    public function test_calculate_sheltered_area(): void
    {
        // TODO: Fix 419 error on testing

        // Define the altitudes data
        $attitudes = [30, 27, 17, 42, 29, 12, 14, 41, 42, 42];

        // Make a POST request to the /calculate route with the altitudes data
        $response = $this->withSession(['_token' => csrf_token()])->post('calculate', [
            '_token' => csrf_token(),
            'altitudes' => $attitudes,
        ]);

        $response->assertStatus(200);

        $response->assertExactJson([
            'altitudes' => $attitudes,
            'shelteredArea' => 6,
            'sheltered' => [false, false, false, false, false, false],
        ]);
    }
}
