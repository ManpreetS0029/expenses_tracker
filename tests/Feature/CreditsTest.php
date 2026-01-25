<?php

use App\Models\User;

test('guests are redirected to the login page when visiting credits', function () {
    $response = $this->get(route('credits'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the credits page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('credits'));
    $response->assertOk();
});
