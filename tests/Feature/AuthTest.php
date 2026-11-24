<?php

test('guest can see the login page', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('user can login with valid credentials', function () {
    $user = createOwner();

    $response = $this->post('/login', [
        'username' => 'uroo',
        'password' => 'uroo1234',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with invalid credentials', function () {
    createOwner();

    $response = $this->from('/login')->post('/login', [
        'username' => 'uroo',
        'password' => 'wrongpassword',
    ]);

    $response->assertRedirect('/login');
    $this->assertGuest();
});

test('authenticated user can logout', function () {
    $user = createOwner();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});
