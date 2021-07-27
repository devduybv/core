<?php

namespace VCComponent\Laravel\Core\Test\Feature;

use VCComponent\Laravel\Vicoders\Core\Test\TestCase;

class RedirectControllerTest extends TestCase
{
    /** @test */
    public function can_redirect_to_admin() {
        $response = $this->get("/admin");
        $response->assertStatus(302);
        $response->assertRedirect('/baseUrl=');
        // dd($response);
    }
}