<?php

use App\Models\User;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->put('/user/profile-information', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->put('/user/profile-information', [
        'name' => 'Test User',
        'email' => $user->email,
    ]);

    $response->assertSessionHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->delete('/user', [
        'password' => 'password',
    ]);

    $response->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->delete('/user', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
