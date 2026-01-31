<?php

use App\Models\User;

test('guests are redirected to the login page when visiting expenses', function () {
    $response = $this->get(route('expenses'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the expenses page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('expenses'));
    $response->assertOk();
});
